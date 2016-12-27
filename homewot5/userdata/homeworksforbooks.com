--- 
customlog: 
  - 
    format: combined
    target: /usr/local/apache/domlogs/homeworksforbooks.com
  - 
    format: "\"%{%s}t %I .\\n%{%s}t %O .\""
    target: /usr/local/apache/domlogs/homeworksforbooks.com-bytes_log
documentroot: /home/homewot5/public_html
group: homewot5
hascgi: 1
homedir: /home/homewot5
ifmodulemoddisablesuexecc: {}

ifmodulemodsuphpc: 
  group: homewot5
ip: 162.144.69.239
owner: root
phpopenbasedirprotect: 1
port: 80
redirectmatch: 
  - 
    redirectmatch: permanent ^(/mailman|/mailman/.*)$ http://box707.bluehost.com$1
scriptalias: 
  - 
    path: /home/homewot5/public_html/cgi-bin
    url: /cgi-bin/
  - 
    path: /home/homewot5/public_html/cgi-bin/
    url: /cgi-bin/
serveradmin: webmaster@homeworksforbooks.com
serveralias: www.homeworksforbooks.com
servername: homeworksforbooks.com
ssl: 1
usecanonicalname: 'Off'
user: homewot5
userdirprotect: ''
