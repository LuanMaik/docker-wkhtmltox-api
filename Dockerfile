FROM newdeveloper/apache-php

RUN apt-get install -y libfontconfig1 libxrender1 libssl1.0-dev
RUN sed -i 's#\/var\/www\/html#'\/var\/www\/html/\public'#g' /etc/apache2/sites-available/000-default.conf