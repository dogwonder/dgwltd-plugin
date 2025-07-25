# sh release.sh minor
BRANCH=`git branch --show-current`
npm --no-git-tag-version version $1 && npm run build && git add . && git commit -a -m "bundle for release" && git push origin ${BRANCH} && git open