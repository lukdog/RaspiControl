# RaspiControl
Web Application that permits users to control a Raspberry from web Browsers.

### What you need
Install on your ***Raspberry*** a web server, mysql server and PHP (_Version 5_), 
if you want to easily manage database you can install also _phpmyadmin_

```bash
# apt-get install apache2
# apt-get install php5 libapache2-mod-php5
# apt-get install mysql-server mysql-client

# apt-get install phpmyadmin
```


### Configuration

###### Create a RaspiControl account (Optional)

If you want you can use an account for your RaspiControl application, so you have to create it:
Example:
```bash
# useradd -d /var/www/RaspiControl -m RaspiControl
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
GRANT ALL PTIVILEGES ON raspicontrol.* TO raspicontrol;
```

###### Install Application
Put Project in ***web-server*** folder (_Example: /var/www/RaspiControl_), and assign right permissions:
       
```bash
# cd /var/www/
# chown RaspiControl -R RaspiControl
```
