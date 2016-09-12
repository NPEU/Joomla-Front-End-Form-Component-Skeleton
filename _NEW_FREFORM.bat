@echo off
@echo Arguments that contain spaces spaces must me enclosed in "".

:promptname
set /p Nm= Enter new component name: 

if [%Nm%]==[] goto checkname

:promptdesc
set /p Ds= Enter new component description:

if [%Ds%]==[] goto checkdesc

php -f _build-new-freform/index.php name=%Nm% description=%Ds%

pause
goto :eof


:checkname
@echo You must enter a name
pause
goto :promptname

:checkdesc
@echo You must enter a description
pause
goto :promptdesc

