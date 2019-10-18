# mysql-summary

mysql-summary es una pequeña aplicación web que muestra el estado y la configuración de un servidor MySQL.

[![Captura de pantalla](https://raw.githubusercontent.com/ramonromancastro/mysql-summary/master/META/screenshot.png)](https://www.mysql.com/)

## Configuración

### Prerrequisitos

mysql-summary require la extensión de PHP mysqli para su ejecución.

Instalar las dependencias y reiniciar el servidor.

```sh
$ yum install php-mysqli
# CentOS 6.x, RHEL 6.x
$ service httpd restart
# CentOS 7.x, RHEL 7.x
$ systemctl restart httpd
```

### Instalación

Descargar y configurar mysql-summary.

```sh
$ cd /var/www/html
$ git clone https://github.com/ramonromancastro/mysql-summary.git
$ chown apache:apache -R mysql-summary
```
## Recomendaciones

Dado que la aplicación solicita usuario y contraseña de acceso a los servidores MySQL para poder realizar la conexión, se recomienda implementar mysql-syummary bajo un servidor seguro (HTTPS).
