# ===============================
# Base: PHP + Apache (estável)
# ===============================
FROM php:8.2-apache

# ===============================
# Variáveis de ambiente úteis
# ===============================
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# ===============================
# Dependências do sistema
# ===============================
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# ===============================
# Extensões PHP necessárias
# ===============================
RUN docker-php-ext-install curl

# ===============================
# Apache config
# ===============================
RUN a2enmod rewrite headers

# ===============================
# Copia TODO o projeto
# (bot.php + assets/)
# ===============================
COPY . /var/www/html/

# ===============================
# Permissões corretas
# ===============================
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ===============================
# Segurança básica
# ===============================
RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf \
    && sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf

# ===============================
# Porta padrão (Render usa 80)
# ===============================
EXPOSE 80

# ===============================
# Healthcheck (opcional, mas bom)
# ===============================
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD curl -f http://localhost/ || exit 1