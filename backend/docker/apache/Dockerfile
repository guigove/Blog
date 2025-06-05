FROM httpd:2.4

# Install required utilities
RUN apt-get update && apt-get install -y --no-install-recommends \
    apache2-utils \
    && rm -rf /var/lib/apt/lists/*

# Enable required modules
RUN sed -i 's/^#\(LoadModule rewrite_module modules\/mod_rewrite.so\)/\1/' /usr/local/apache2/conf/httpd.conf \
    && sed -i 's/^#\(LoadModule proxy_module modules\/mod_proxy.so\)/\1/' /usr/local/apache2/conf/httpd.conf \
    && sed -i 's/^#\(LoadModule proxy_fcgi_module modules\/mod_proxy_fcgi.so\)/\1/' /usr/local/apache2/conf/httpd.conf \
    && sed -i 's/^#\(LoadModule headers_module modules\/mod_headers.so\)/\1/' /usr/local/apache2/conf/httpd.conf

# Copy custom configuration
COPY apache-config.conf /usr/local/apache2/conf/httpd.conf

# Expose port 80
EXPOSE 80

# Command to start Apache
CMD ["httpd-foreground"] 