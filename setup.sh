#!/bin/sh

# install php gd library for graphics if not installed
#yum install php55w_gd


# write permission need to following directories
chmod -R 777 ci_sessions/
chmod -R 777 downloads/
chmod -R 777 public/downloads/
chmod -R 777 public/uploads/

# set execution permission to this jar file 
chmod u+x mysqldump.jar
