# PHP CMS

**Simple CMS website**

![My Skills](https://go-skill-icons.vercel.app/api/icons?i=apache,php,mariadb,nodejs,js,sass,gulp)

## Requirements 🛠️⚙️

- Apache 2.4+
- PHP 8.x
- PHP extensions: mysqli, pdo
- MariaDB (or MySQL)
- Node

## Linux Setup (For Local Development) 🐧🧰

### **1. Create virtual host** 📡

> _`sites-available`_ is a directory inside your Apache installation (usually _`/etc/apache2/sites-available`_). Check where Apache is installed on your system.

```sh
sudo nano /path/to/sites-available/project-name.conf
```

```apacheconf
<VirtualHost *:80>
  DocumentRoot "/path/to/project"
  ServerName project.name

  <Directory "/path/to/project">
      Require all granted
      AllowOverride All
  </Directory>

  php_flag display_errors On  # Only for development
  php_value error_reporting -1
</VirtualHost>
```

### **2. Enable site** 🔄

```sh
sudo a2ensite project-name.conf
sudo systemctl reload apache2
```

### **3. Update /etc/hosts file** 📝

```sh
sudo nano /etc/hosts
```

_Add to map local domain to localhost_ 🔗

```text
0.0.0.0        project.name
```

### **4. Grant permissions for PHP (If required)** 🪪

```sh
sudo chown -R my-user:www-data /path/to/project
sudo chmod -R 775 /path/to/project
```

## **5. Install node dependencies** 📥

```sh
npm i
```

### **6. Run dev for browser syncing and sass compiling**

```sh
npm run dev
```

_Everything should be set and ready to load \*\*_`project.name`_\*\* on your browser 🌐_
