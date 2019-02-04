FROM newdeveloper/apache-php
MAINTAINER Luan Maik <luanmaik1994@gmail.com>

#Install libs to better wkhtmltopdf render
RUN apt-get install -y libfontconfig1 libxrender1 libssl1.0-dev

#Change root default apache site
RUN sed -i 's#\/var\/www\/html#'\/var\/www\/html/\public'#g' /etc/apache2/sites-available/000-default.conf

#Add application to image
ADD . /var/www/html