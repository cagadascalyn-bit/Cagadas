# Profile Picture — Base64 Storage

## Why Base64 in the Database?

Platforms like **Railway, Render, Heroku, and Fly.io** use ephemeral (temporary) containers.
Any file uploaded to the server's filesystem (`storage/app/public`) is **deleted on every redeploy or restart**.

Storing the image as a base64 string directly in the database solves this because:
- The database persists across deployments
- No external storage service (S3, Cloudinary) is needed
- No API keys or configuration required
- Works identically on local and production

---

## How It Works

### Upload Flow
1. User selects a JPEG/PNG image (max 2MB) on the Profile page
2. `ProfileController::update()` reads the raw binary of the file
3. Converts it to base64 with the data URI prefix: `data:image/jpeg;base64,...`
4. Saves the full string into the `profile_picture_base64` column (LONGTEXT)

### Display Flow
Views check in this priority order:
1. `profile_picture_base64` — base64 string stored in DB (used directly in `<img src="">`)
2. `profile_picture` — legacy file path (backward compatibility for existing uploads)
3. Placeholder — colored circle with the user's initial letter

---

## Database Column

```sql
profile_picture_base64 LONGTEXT NULL
```

A 2MB image encodes to ~2.7MB of base64 text. LONGTEXT supports up to 4GB, so this is safe.

---

## Files Changed

| File | Change |
|------|--------|
| `database/migrations/2024_01_01_000004_add_profile_picture_base64_to_users_table.php` | New migration adding the column |
| `app/Models/User.php` | Added `profile_picture_base64` to `$fillable` |
| `app/Http/Controllers/ProfileController.php` | Converts upload to base64, stores in DB |
| `resources/views/layouts/app.blade.php` | Topbar avatar checks base64 first |
| `resources/views/profile/show.blade.php` | Profile card checks base64 first |
| `cagadas_db.sql` | Added column to schema |

---

## Local Setup (XAMPP)

Since you already have the SQL imported, run this to add the new column:

```sql
USE cagadas_db;
ALTER TABLE users ADD COLUMN profile_picture_base64 LONGTEXT NULL AFTER profile_picture;
```

Or drop and re-import the updated `cagadas_db.sql` file.

---

## Railway Deployment Steps

1. Push your code to GitHub
2. Connect the repo to Railway
3. Add a **MySQL plugin** in Railway dashboard
4. Set these environment variables in Railway:

```
APP_KEY=base64:your-key-here
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=<railway-mysql-host>
DB_PORT=<railway-mysql-port>
DB_DATABASE=<railway-db-name>
DB_USERNAME=<railway-db-user>
DB_PASSWORD=<railway-db-password>
SESSION_DRIVER=database
```

5. In Railway's deploy settings, set the start command:
```
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

6. Import `cagadas_db.sql` via Railway's MySQL console or a tool like TablePlus

---

## Validation Rules

```php
'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
```

- Only JPEG and PNG accepted
- Maximum 2MB file size
- Validated before any processing

---

## Trade-offs

| | Base64 in DB | File Storage | Cloud (S3/Cloudinary) |
|---|---|---|---|
| Survives redeploy | ✅ | ❌ | ✅ |
| No config needed | ✅ | ✅ | ❌ |
| DB size impact | ⚠️ slight | ✅ none | ✅ none |
| Best for | School projects, small apps | Local only | Production at scale |
