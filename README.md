# RaspiControl
Web Application that permits users to control a Raspberry from web Browsers.

### What you need
Install on your ***Raspberry*** a web server, mysql server and PHP (_Version 5_), 
if you want to easily manage database you can install also _phpmyadmin_

For running command with root permissions from application you will need to install also ***inotify-tools***

### Installation

###### Create a RaspiControl account (Optional)

Download _.zip_ file and extract it in ***web-server*** folder ( _Example: /var/www_ ), a raspicontrol directory will be created.

If you want you can use an account for your RaspiControl application, so you have to create it, and then adjust permissions:
Example:
```bash
# useradd -d /var/www/raspirontrol -m raspirontrol
# chown raspicontrol:raspicontrol -R /var/www/raspicontrol
```

###### Configuration of Database
In ***mysql*** create an account for the application with a ***strong*** password
```sql
CREATE USER raspicontrol@localhost IDENTIFIED BY 'password';
```

and a database:
```sql
CREATE DATABASE rapicontrol;
```

in the end configure privileges:
```sql
GRANT ALL PRIVILEGES ON raspicontrol.* TO raspicontrol;
```

###### Install Application
Move in install folder and then install raspicontrol using the ***install.sh*** script
       
```bash
# cd raspicontrol/install
# chmod +x install.sh
# ./install.sh
```

Raspicontrol will be installed in your Raspberry, if you want to use it as daemon you can use _-d_ argument

```bash
# ./install.sh -d
```

###### Configure RaspiControl

Now you have to configure application with DB credentials and other things, 
you can do that using ***config.json*** file in _/etc/RaspiControl/config.json_
```json
{
  "Database": {
    "DB_Host": "localhost",
    "DB_Name": "dbname",
    "DB_Username": "user",
    "DB_Password": "password"
  }
}
```

than you have also to configure which actions can be performed with application, for example you can set some ***services***
that can be started, stopped, reloaded or simple actions, for example reboot, or poweroff.
```json
{
  "services": {
    "apache2": "start|stop|force-reload",
    "vsftpd": "start|stop"
  },
  "actions": {
    "reboot": "reboot"
  }
}
```

If you want to better understand how services and actions have to be configured please, ***read Wiki***.