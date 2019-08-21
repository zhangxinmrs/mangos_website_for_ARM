# mangos_website_for_ARM
apt-get install nginx php git php-cli php-fpm php-gd php-curl php-cgi php-mcrypt

vi /etc/nginx/sites-available/default
将其中的如下内容
location / {
                # First attempt to serve request as file, then
                # as directory, then fall back to displaying a 404.
                try_files $uri $uri/ =404;
        }
替换为
location / {
                index  index.html index.htm index.php default.html default.htm default.php;
        }
 
        location ~ .*\.php(\/.*)*$ {
                #fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/var/run/php7.3-fpm.sock;
                fastcgi_index index.php;
                include fastcgi_params;
                set $real_script_name $fastcgi_script_name;
                if ($fastcgi_script_name ~ "(.+?\.php)(/.*)") {
                        set $real_script_name $1;
                        set $path_info $2;
                }
                fastcgi_param SCRIPT_FILENAME $document_root$real_script_name;
                fastcgi_param SCRIPT_NAME $real_script_name;
                fastcgi_param PATH_INFO $path_info;
        }
cd /var/www/html
git clone https://github.com/zhangxinmrs/Lightshope-for-ARM.git
