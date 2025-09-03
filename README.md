# PHP CMS

**Simple CMS website**

![My Skills](https://go-skill-icons.vercel.app/api/icons?i=apache,php,mariadb,html,css,js)

## Requirements 🛠️⚙️

- Apache 2.4+
- PHP 8.x
- PHP extensions: mysqli, pdo
- MariaDB (or MySQL)

## Linux Setup (For Local Development) 🐧🧰

1. Create virtual host 📡

> _`sites-available`_ is a directory inside your Apache installation (usually _`/etc/apache2/sites-available`_). Check where Apache is installed on your system.

```sh
sudo nano /path/to/sites-available/project-name.conf
```

```apacheconf
<VirtualHost *:8080> # Or any desired port
  DocumentRoot "/path/to/project"
  ServerName projectName.localhost

  <Directory "/path/to/project">
      Require all granted
      AllowOverride All
  </Directory>

  php_flag display_errors On  # Only for development
  php_value error_reporting -1
</VirtualHost>
```

2. Enable site 🔄

```sh
sudo a2ensite project-name.conf
sudo systemctl reload apache2
```

3. Update /etc/hosts file 📝

```sh
sudo nano /etc/hosts
```

_Add to map local domain to localhost_ 🔗

```text
127.0.0.1 projectName.localhost
```

4. Grant permissions for PHP 🪪

```sh
sudo chown -R my-user:www-data /path/to/project
sudo chmod -R 775 /path/to/project
```

5. Everything should be set and ready to load **_`localhost:8080`_** (or the assigned port) on your browser 🌐
