# Loxion

E-learning web application developed during the **Seshego Digital Hub Hackathon**. Focus areas:
- Backend & database integration
- Secure login/roles
- Student/teacher workflows

## Tech Stack
- PHP
- MySQL
- HTML/CSS/JavaScript

## Features
- User interfaces and server-side routes implemented in PHP.
- MySQL schema and queries included for persistent storage.
- Custom stylesheets for UI/UX.
- Front-end interactivity powered by JavaScript.

## Project Structure
This repository preserves the original hackathon structure. Additional meta files added:
- `.gitignore` (ignores secrets, vendor, OS files)
- `LICENSE` (MIT)
- `SECURITY.md`, `CODE_OF_CONDUCT.md`

## Getting Started

### Prerequisites
- XAMPP/WAMP/LAMP with PHP and MySQL
- A web browser

### Setup
1. Clone the repo and copy files into your web root (e.g., `htdocs` for XAMPP or `www` for WAMP).
1. Create a MySQL database (e.g., `loxion_db`).
1. Import the SQL file(s) found in the `/database` or project folder into your MySQL database.
1. Configure database credentials in the config file (e.g., `config.php`, `database.php`) and ensure credentials are **not committed**.
1. Start Apache (and MySQL) and open the entry file in the browser (for example `index.php`).

**Likely entry files**
- `Loxion/Chatbot/index.php`
- `Loxion/Login_Logout/AI/index.php`
- `Loxion/Student/index.php`

**Likely config files**
- `Loxion/Database/database_connection.php`
- `Loxion/Login_Logout/AI/database.php`

## Environment & Secrets
- Do **not** commit `.env` or plaintext credentials.
- Put DB credentials in environment variables or an untracked config.
- See `.gitignore` which already excludes `.env` and keys.

## Screenshots
> Add screenshots of key pages here: `docs/screenshots/` (create folder).

## Contributing
Pull requests are welcome. For major changes, open an issue first to discuss the proposal.

## License
MIT © Matome Michael Ramashiya

---
### Security Review Summary (generated)
⚠️ Potential secrets found in:
- `Loxion/Admin/Generate_token.php` → match: **token** (…rade']) ? $_POST['grade'] : NULL;      $token = bin2hex(random_bytes(3));      if ($r…)
- `Loxion/Admin/Generate_token.php` → match: **token** (… $query = "INSERT INTO pre_registration_tokens (Token, Role, Grade, Classroom, Genera…)
- `Loxion/Admin/Generate_token.php` → match: **Token** (…= "INSERT INTO pre_registration_tokens (Token, Role, Grade, Classroom, GeneratedOn, E…)
- `Loxion/Admin/Generate_token.php` → match: **token** (…mysqli_stmt_bind_param($stmt, "sssss", $token, $role, $grade, $selectedClassroom, $ex…)
- `Loxion/Admin/Generate_token.php` → match: **Token** (…mt)) {             echo "<script>alert('Token generated successfully: $token');</scri…)
- `Loxion/Admin/Generate_token.php` → match: **token** (…t>alert('Token generated successfully: $token');</script>";         } else {         …)
- `Loxion/Admin/pre_registration.php` → match: **Token** (…ef="pre_style.css">     <title>Generate Token</title>      <script>         function …)
- `Loxion/Admin/pre_registration.php` → match: **Token** (…         <button type="submit">Generate Token</button>     </form>      <?php   inclu…)
- `Loxion/Admin/pre_registration.php` → match: **Token** (…t(); }     $query = "         SELECT pt.TokenID, pt.Token, pt.Role, pt.Grade, pt.Clas…)
- `Loxion/Admin/pre_registration.php` → match: **Token** (…query = "         SELECT pt.TokenID, pt.Token, pt.Role, pt.Grade, pt.Classroom, pt.Us…)
- `Loxion/Admin/pre_registration.php` → match: **token** (….Lastname         FROM pre_registration_tokens pt         LEFT JOIN users u ON pt.Use…)
- `Loxion/Admin/pre_registration.php` → match: **Token** (…  echo "<table>";         echo "<tr><th>TokenID</th><th>Token</th><th>Role</th><th>Gr…)
- `Loxion/Admin/pre_registration.php` → match: **Token** (…;         echo "<tr><th>TokenID</th><th>Token</th><th>Role</th><th>Grade</th><th>Clas…)
- `Loxion/Admin/pre_registration.php` → match: **token** (…    $action = "<form action='reactivate_token.php' method='post' style='display:inlin…)
- `Loxion/Admin/pre_registration.php` → match: **token** (…             <input type='hidden' name='token_id' value='" . $row['TokenID'] . "'>   …)
- (+ 71 more matches not shown)
