# CS2102 Carpooling Project

##Set up for apache web server
Let the project directory be <PROJECT_DIRECTORY>
Let the bitnami directory be <BITNAMI_DIRECTORY>

Add a line to <BITNAMI_DIRECTORY>\apache2\conf\bitnami\bitnami-apps-prefix.conf
`Include "<PROJECT_DIRECTORY>/conf/httpd-prefix.conf"`

Create three files in <PROJECT_DIRECTORY>/conf/
1. httpd-app.conf
```
<Directory <PROJECT_DIRECTORY>/htdocs/>
    Options Indexes MultiViews
    AllowOverride All
    <IfVersion < 2.3 >
    Order allow,deny
    Allow from all
    </IfVersion>
    <IfVersion >= 2.3>
    Require all granted
    </IfVersion>
</Directory>
```
2. httpd-prefix.conf
```
Alias /carpool/ "<PROJECT_DIRECTORY>/htdocs/"
Alias /carpool "<PROJECT_DIRECTORY>/htdocs/"

Include "<PROJECT_DIRECTORY>/conf/httpd-app.conf"
```
3. httpd-vhosts.conf
```
<VirtualHost *:80>
  ServerName carpool.example.com
  DocumentRoot "<PROJECT_DIRECTORY>/htdocs"
  Include "<PROJECT_DIRECTORY>/conf/httpd-app.conf"                                                                                           
</VirtualHost>
```

Link to index is localhost/carpool (similar to the project demo)