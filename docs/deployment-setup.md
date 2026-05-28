# Deployment einrichten

## 1. GitHub Repository erstellen

1. GitHub → "New repository" → Name: `dogsitter`
2. Lokal initialisieren:
   ```bash
   cd Z:\Webserver\aktuell\allinkl\dogsitter
   git init
   git remote add origin https://github.com/DEIN-USERNAME/dogsitter.git
   git add .
   git commit -m "initial setup"
   git push -u origin main
   ```

## 2. all-inkl SSH-Key einrichten

SSH-Key erzeugen (einmalig, lokal):
```bash
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/dogsitter_deploy
```
Das erzeugt zwei Dateien:
- `~/.ssh/dogsitter_deploy` → **privater Key** (kommt zu GitHub)
- `~/.ssh/dogsitter_deploy.pub` → **öffentlicher Key** (kommt zu all-inkl)

Öffentlichen Key zu all-inkl hinzufügen:
- all-inkl KAS → SSH-Schlüssel → Inhalt von `dogsitter_deploy.pub` einfügen

## 3. GitHub Secrets eintragen

GitHub Repo → Settings → Secrets and variables → Actions → "New repository secret"

| Secret Name | Wert |
|---|---|
| `ALLINKL_SSH_KEY` | Inhalt von `~/.ssh/dogsitter_deploy` (privater Key, alles inkl. BEGIN/END) |
| `ALLINKL_HOST` | SSH-Hostname von all-inkl (z.B. `ssh.server123.all-inkl.com`) |
| `ALLINKL_USER` | SSH-Benutzername von all-inkl |
| `ALLINKL_PATH` | Pfad auf dem Server z.B. `/www/htdocs/xxx/dogsitter/api` |

## 4. Vercel einrichten

1. vercel.com → mit GitHub verbinden → "New Project"
2. Repository `dogsitter` → Root Directory: `frontend`
3. Nach dem ersten Deploy: Vercel → Project Settings → General → IDs notieren

Dann in GitHub Secrets:

| Secret Name | Wo finden |
|---|---|
| `VERCEL_TOKEN` | vercel.com → Account Settings → Tokens → "Create" |
| `VERCEL_ORG_ID` | vercel.com → Project Settings → General → "Team ID" |
| `VERCEL_PROJECT_ID` | vercel.com → Project Settings → General → "Project ID" |

## 5. Fertig!

Ab jetzt: Push auf `main` → Tests laufen → bei Erfolg automatisches Deployment.
