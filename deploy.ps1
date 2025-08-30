# deploy.ps1 — portable + safe
# Runs from the script's own folder (works on C: or D:)
# Builds a conventional commit message and pushes to DreamHost + GitHub.

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

# 1) Go to the script folder (portable across machines)
$repoRoot = if ($PSScriptRoot) { $PSScriptRoot } else { Split-Path -Parent $MyInvocation.MyCommand.Path }
Set-Location $repoRoot

# 2) Show where we are (helps sanity-check)
Write-Output "Repo: $(Get-Location)"

# 3) Stage everything
git add -A

# 4) Check if anything is staged
#    If no staged changes, skip the commit step
git diff --cached --quiet 2>$null
$hasChanges = ($LASTEXITCODE -ne 0)

if ($hasChanges) {
  Write-Output "Select commit type:"
  Write-Output "1) feat    - new feature"
  Write-Output "2) fix     - bug fix"
  Write-Output "3) style   - UI/formatting"
  Write-Output "4) refactor- code improvement"
  Write-Output "5) docs    - documentation"
  Write-Output "6) chore   - maintenance"
  Write-Output "7) perf    - performance"
  $typeChoice = Read-Host "Enter number"

  switch ($typeChoice) {
    "1" { $type = "feat" }
    "2" { $type = "fix" }
    "3" { $type = "style" }
    "4" { $type = "refactor" }
    "5" { $type = "docs" }
    "6" { $type = "chore" }
    "7" { $type = "perf" }
    default { $type = "chore" }
  }

  $msg = Read-Host "Enter short commit message"
  $commitMessage = "${type}: ${msg}"   # <-- braces fix the $type: parsing issue

  git commit -m "$commitMessage"
} else {
  Write-Output "No changes to commit. Pushing only..."
}

# 5) Push: DreamHost (if remote exists)
try {
  $null = git remote get-url dreamhost 2>$null
  Write-Output "Pushing to DreamHost..."
  git push dreamhost main
} catch {
  Write-Warning "Skipping DreamHost push (remote 'dreamhost' not found)."
}

# 6) Push: GitHub (if remote exists)
try {
  $null = git remote get-url origin 2>$null
  Write-Output "Pushing to GitHub..."
  git push origin main
} catch {
  Write-Warning "Skipping GitHub push (remote 'origin' not found)."
}

Write-Output "$([char]0x2705) Deployment complete!"
