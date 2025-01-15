# RUNASERVER
## INSTALAR
```
cd ~ && git clone https://desarrollorunachay@bitbucket.org/desarrollorunachay/runaserver.git
rm ~/runaserver/docker/mysql/Empty
cd ~/runaserver/html/ 
```

## COPIAR
copia el proyecto de runachay en:
```~/runaserver/html/``` 

debe quedar asi:
```runaserver/html/runachay7```

# ACCESOS
[http://localhost/runachay7/public](http://localhost/runachay7/public)



## PREPARA MYSQL
Entra en Adminer [http://localhost/filemanager/adminer.php?server=mysql&username=root](http://localhost/filemanager/adminer.php?server=mysql&username=root)

Ubica las credenciales (por defecto es **usuario: root clave: 12345678**)
### EJECUTA EL SIGUIENTE SQL

```SET GLOBAL log_bin_trust_function_creators = 1;```

# INICIAR
```~/runaserver/server start```

# DOCS
### Run server :
```~/runaserver/server start```

### Stop server :
```~/runaserver/server stop```

### Restart server :
```~/runaserver/server restart```

### Replace Config? :
```~/runaserver/server rebuild```

### Composer command (Example) :
```~/runaserver/server run composer install```

### Artisan command (Example) :
```~/runaserver/server run php artisan key:generate```


# IMPORTAR UNA BASE DE DATOS
para importar una base de datos, ubicala en 
```~/runaserver/html/filemanager```  con el nombre ```adminer.sql[.gz]``` (el archivo puede o no estar comprimido)

ir a 
[http://localhost/filemanager/adminer.php?server=mysql&username=root&import=](http://localhost/filemanager/adminer.php?server=mysql&username=root&import=) y hacer click en ejecutar *desde servidor*


# CAMBIO DE VERSION DE PHP
para cambiar la version cambie en el archivo .env ubicado en la carpeta **docker**
 y ejecute el siguiente comando ```~/runaserver/server rebuild``` esto eliminara los contenedores y los volvera a crear con la nueva configuracion (Tus bases de datos no se veran afectadas)