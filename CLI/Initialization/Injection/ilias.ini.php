; <?php exit; ?>
[server]
http_path = "http://localhost/travis"
absolute_path = "/var/www/gitilias/"
presetting = ""
timezone = "Europe/Berlin"

[clients]
path = "data"
inifile = "client.ini.php"
datadir = "/var/iliasdata"
default = "travis"
list = "0"

[setup]
pass = "0cf3cf347a5403a72141bc2d8c5e893f"

[tools]
convert = "/usr/bin/convert"
zip = "/usr/bin/zip"
unzip = "/usr/bin/unzip"
java = "/usr/bin/java"
htmldoc = "/usr/bin/htmldoc"
ffmpeg = ""
ghostscript = "/usr/bin/gs"
latex = ""
vscantype = "none"
scancommand = ""
cleancommand = "root"

[log]
path = "/var/iliasdata/travis"
file = "ilias.log"
enabled = "1"
level = "WARNING"

[debian]
data_dir = "/var/opt/ilias"
log = "/var/log/ilias/ilias.log"
convert = "/usr/bin/convert"
zip = "/usr/bin/zip"
unzip = "/usr/bin/unzip"
java = ""
htmldoc = "/usr/bin/htmldoc"
ffmpeg = "/usr/bin/ffmpeg"

[redhat]
data_dir = ""
log = ""
convert = ""
zip = ""
unzip = ""
java = ""
htmldoc = ""

[suse]
data_dir = ""
log = ""
convert = ""
zip = ""
unzip = ""
java = ""
htmldoc = ""
