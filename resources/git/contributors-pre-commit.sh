#!/usr/bin/env bash
#
# Summary:  Create CONTRIBUTORS.md on commit
# Author:   DevOpSec <https://github.com/devopsec>
# Usage:    Copy to your repo in <repo>/.git/hooks/pre-commit
#           Alternatively the create_contributors function can be used independently by running script
#

# project root
PROJECT_ROOT="$(git rev-parse --show-toplevel)"
# destination file
CONTRIBUTING_FILE="CONTRIBUTORS.md"

create_contributors() {
    (cat <<EOF
## Thank you to all contributors for your hard work

### Contributors

$(git shortlog -sn | grep -oP '[\s\d]*\K.*'| awk '{for (i=1; i<=NF; i++) print "- " $0}' | sort)
EOF
    ) > ${CONTRIBUTING_FILE}

    git add ${CONTRIBUTING_FILE}
}

create_contributors
exit 0
