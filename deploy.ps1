# deploy.ps1
Set-Location "D:\Dev\Brodys_Website\brodys.site"

# Stage everything
git add -A

# Check if there is anything to commit
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
  $commitMessage = "${type}: ${msg}"

  git commit -m "$commitMessage"
} else {
  Write-Output "No changes to commit. Pushing only..."
}

Write-Output "Pushing to DreamHost..."
git push dreamhost main

Write-Output "Pushing to GitHub..."
git push origin main

Write-Output "$([char]0x2705) Deployment complete!"
