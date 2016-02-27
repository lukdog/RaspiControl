#!/usr/bin/env python
import subprocess
import os
from shutil import rmtree
import pwd
import grp
import logging
import logging.handlers
import json

__author__ = 'Luca Doglione'

# Logging Settings
LOG_FILENAME = "/var/log/raspicontrol.log"
LOG_LEVEL = logging.INFO

logger = logging.getLogger(__name__)
logger.setLevel(LOG_LEVEL)
handler = logging.handlers.RotatingFileHandler(LOG_FILENAME, maxBytes=1024000, backupCount=3)
formatter = logging.Formatter('%(asctime)s %(levelname)-8s %(message)s')
handler.setFormatter(formatter)
logger.addHandler(handler)


# TODO manage output???
class RaspiControl:
    services = {}
    actions = {}
    runningScriptDir = ""

    def __init__(self, directory, services, actions):
        """Function that init the application"""
        self.services = services
        self.actions = actions
        self.runningScriptDir = directory

        logger.debug("Initialization of raspicontrol")
        logger.info("There are " + str(len(services)) + " services, " + str(
            len(actions)) + " actions. Running Dir is: " + self.runningScriptDir)

        if os.path.exists(self.runningScriptDir):
            rmtree(self.runningScriptDir)
        os.makedirs(self.runningScriptDir)

        uid = pwd.getpwnam("www-data").pw_uid
        gid = grp.getgrnam("www-data").gr_gid

        for val in self.services:
            output = subprocess.check_output(['ps', '-A'])
            if val in output:
                open(self.runningScriptDir + "/" + val, "w").close()
                os.chown(self.runningScriptDir + "/" + val, uid, gid)

        open(self.runningScriptDir + "/.output", "w").close()
        os.chown(self.runningScriptDir + "/.output", uid, gid)
        os.chown(self.runningScriptDir, uid, gid)

        logger.debug("Initialization Completed")

        return

    def create_event(self, arg):
        """Function that react to a CREATE event"""
        line = arg.strip()
        val = line.split(" ")
        name = val[len(val) - 1]

        if self.services.__contains__(name):
            if len(self.services[name]) >= 0:
                action = self.services[name].split("|")[0]
                cmd = "service " + name + " " + action
                logger.info("Requested cmd: " + cmd)
                if os.system(cmd) == 0:
                    os.system("echo \"Successfully Executed\" > " + self.runningScriptDir + "/.output")
                else:
                    os.system("echo \"An error occurred retry later\" > " + self.runningScriptDir + "/.output")
        elif self.actions.__contains__(name):
            if len(self.actions[name]) >= 0:
                action = self.actions[name]
                logger.info("Requested action: " + action)
                if os.system(action) == 0:
                    os.system("echo \"Successfully Executed\" > " + self.runningScriptDir + "/.output")
                else:
                    os.system("echo \"An error occurred retry later\" > " + self.runningScriptDir + "/.output")
        elif name != ".output":
            logger.error("Received a not configured command: " + name)
        return

    def remove_event(self, arg):
        """Function that react to a DELETE event"""
        line = arg.strip()
        val = line.split(" ")
        name = val[len(val) - 1]

        if self.services.__contains__(name):
            if len(self.services[name]) >= 1:
                action = self.services[name].split("|")[1]
                cmd = "service " + name + " " + action
                logger.info("Requested cmd: " + cmd)
                if os.system(cmd) == 0:
                    os.system("echo \"Successfully Executed\" > " + self.runningScriptDir + "/.output")
                else:
                    os.system("echo \"An error occurred retry later\" > " + self.runningScriptDir + "/.output")
        elif name != ".output":
            logger.error("Received a not configured command: " + name)
        return

    def modify_event(self, arg):
        """Function that react to a MODIFY event"""
        line = arg.strip()
        val = line.split(" ")
        name = val[len(val) - 1]

        if self.services.__contains__(name):
            if len(self.services[name]) >= 2:
                action = self.services[name].split("|")[2]
                cmd = "service " + name + " " + action
                logger.info("Requested cmd: " + cmd)
                if os.system(cmd) == 0:
                    os.system("echo \"Successfully Executed\" > " + self.runningScriptDir + "/.output")
                else:
                    os.system("echo \"An error occurred retry later\" > " + self.runningScriptDir + "/.output")
        elif name != ".output":
            logger.error("Received a not configured command: " + name)
        return


# Start point for script
if __name__ == "__main__":

    data = {}
    try:
        # Read JSON Configuration
        try:
            with open("/etc/RaspiControl/config.json") as data_file:
                data = json.load(data_file)

            logger.debug("Config file loaded")

        except Exception as e:
            logger.fatal("Impossible to Read config File")

        if data.__contains__("logLevel"):
            LOG_LEVEL = logging.getLevelName(data["logLevel"])
            logger.setLevel(LOG_LEVEL)
        control = RaspiControl(data["runningScriptDir"], data["services"], data["actions"])

        while True:
            process = subprocess.Popen(
                    ["inotifywait", "-q", "-e", "create", "-e", "modify", "-e", "delete", "-e", "delete_self",
                     control.runningScriptDir], stdout=subprocess.PIPE)
            event = process.communicate()[0]
            if event.find("CREATE") != -1:
                logger.debug("Received CREATE event: " + event.strip())
                control.create_event(event)
            elif event.find("DELETE_SELF") != -1:
                logger.debug("Received DELETE_SELF event: " + event.strip())
                logger.error(control.runningScriptDir + " was eliminated")
                control = RaspiControl(data["runningScriptDir"], data["services"], data["actions"])
            elif event.find("DELETE") != -1:
                logger.debug("Received DELETE event: " + event.strip())
                control.remove_event(event)
            elif event.find("MODIFY") != -1:
                logger.debug("Received MODIFY event: " + event.strip())
                control.modify_event(event)
            else:
                logger.debug("Received UNKNOWN event: " + event.strip())
    except KeyboardInterrupt as e:
        logger.warn("Terminated with keyboard interrupt")
        exit(0)
    except SystemExit as e:
        logger.warn("System has terminated RaspiControl")
    except Exception as e:
        logger.fatal("Fatal Error, RaspiControl Terminated: " + e.message)
