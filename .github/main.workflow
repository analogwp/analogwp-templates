workflow "New workflow" {
  on = "push"
  resolves = ["NPM Build"]
}

action "NPM Install" {
  uses = "actions/npm@de7a3705a9510ee12702e124482fad6af249991b"
  runs = "npm install"
}

action "NPM Build" {
  uses = "actions/npm@de7a3705a9510ee12702e124482fad6af249991b"
  runs = "npm run build"
  needs = ["NPM Install"]
}
