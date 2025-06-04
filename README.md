# 📁 Visualizador do Ping

**Visualizador do Ping** é um sistema web desenvolvido para facilitar a navegação, visualização e download de documentos PDF armazenados em um servidor Linux, organizados por departamentos, mesas, status e processos. O sistema conta com controle de acesso, interface amigável e funcionalidades avançadas de pesquisa e exibição de arquivos.

---

## 🚀 Funcionalidades Principais

- 🔐 Autenticação de usuários com permissões por departamento e mesa.
- 📂 Navegação por diretórios estruturados (estilo "Explorador de Arquivos").
- 🔍 Busca por processos com autocomplete.
- 📄 Visualização de arquivos PDF diretamente no navegador (modal).
- 🧾 Listagem de documentos por processo.
- ⬇️ Download individual ou em lote de arquivos.
- 👤 Gestão de usuários e permissões (modo administrador).
- 📊 Logs de atividades de usuários.

---
## 🧱 Estrutura do Projeto

```
/
├── admin/
│   ├── editar_usuario.php
│   ├── logs.php
│   └── usuarios.php
│
├── css/
│   ├── admin.css
│   ├── admin_nav.css
│   ├── dashboard.css
│   ├── delete_modal.css
│   ├── index.css
│   ├── login.css
│   ├── logout.css
│   ├── logs.css
│   ├── modal.css
│   └── style.css
│
├── img/
│   ├── icone.ico
│   └── logo.png
│
├── includes/
│   ├── admin_auth.php
│   ├── admin_nav.php
│   ├── auth.php
│   ├── db.php
│   └── log.php
│
├── js/
│   ├── modal.js
│   ├── modalListagem.js
│   └── script.js
│
├── .htaccess
├── README.md
├── baixar_todos.php
├── buscar_processos.php
├── config.php
├── dashboard.php
├── index.php
├── listar_arquivos_processo.php
├── login.php
├── logout.php
└── portal_db.sql
```


No sistema, esse caminho é mapeado para o Apache como:  
```
Alias /arquivos/ /var/ping/dados/senarto/
```

---
## 🛠️ Tecnologias Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript (modais e AJAX)
- **Backend:** PHP 8.x
- **Banco de Dados:** MySQL
- **Servidor Web:** Apache2 em Linux Debian
- **Autenticação e Permissões:** Baseado em MySQL
- **Modal Viewer:** JavaScript customizado com dois modais (`fileModal` e `modalListagem`)

---

## ⚙️ Como Executar o Projeto

### 1. Requisitos

- Apache2
- PHP 8.x
- MySQL Server
- Sistema Linux (ex: Debian)
- Permissões de leitura para `/var/ping/dados/senarto/`

### 2. Clonar o Repositório

```bash
[git clone https://github.com/seuusuario/visualizador-ping.git](https://github.com/davidchaves3/Visualizador_de_Documentos.git)
```

### 3. Configurar o Apache

```apache
Alias /arquivos/ /var/ping/dados/senarto/
<Directory /var/ping/dados/senarto/>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
```

### 4. Importar o Banco de Dados

Importe o script SQL disponível em `/sql/banco.sql` para configurar tabelas de usuários, permissões e logs:

```bash
mysql -u root -p nome_do_banco < sql/banco.sql
```

### 5. Configurar o acesso ao banco no PHP

No arquivo `config/database.php`, defina:

```php
$host = 'localhost';
$db = 'nome_do_banco';
$user = 'usuario';
$pass = 'senha';
```

---

## 👤 Permissões

O acesso a processos é controlado por permissões no banco. Cada usuário pode:

- Ver processos de sua mesa e status
- Ter acesso a outros departamentos (caso autorizado)
- Visualizar e baixar apenas os arquivos permitidos

---

## 📸 Demonstração

> Em breve...

---

## 👨‍💻 Desenvolvido por

David Almeida Chaves  
[GitHub](https://github.com/davidchaves3) | [LinkedIn](https://www.linkedin.com/in/david-almeida-chaves-2a8474246/)

---

## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
