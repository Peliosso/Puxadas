# Imagem oficial PHP com Apache
FROM php:8.2-apache

# Ativa mod_rewrite (boa prática)
RUN a2enmod rewrite

# Copia o bot para a pasta pública do Apache
COPY bot.php /var/www/html/index.php

# Permissões
RUN chown -R www-data:www-data /var/www/html

# Porta padrão do Apache
EXPOSE 80
