Write-Output "Pushing to DreamHost..."
git push dreamhost main

Write-Output "Pushing to GitHub..."
git push origin main

Write-Output "$([char]0x2705) Deployment complete!"
