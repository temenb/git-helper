<?php

$output = '';
switch ($argv[1] ?? null) {
    case 'commit' :
        $branch = trim(`git rev-parse --abbrev-ref HEAD`);
        $comment = trim(stripslashes($argv[2] ?? null));
        $output .= 'git add . '
            . '&& git commit -am "' . $branch . "\n" . $comment . '" '
            . "\\\n&& git push origin $branch"
            . "\n";
        break;
    case 'merge' :
        $sourceBranch = trim(`git rev-parse --abbrev-ref HEAD`);
        $targetBranch = trim(stripslashes($argv[2] ?? null));
        $output .= "git branch -D $targetBranch "
            . "&& git fetch origin "
            . "&& git checkout origin/$targetBranch "
            . "&& git checkout -b $targetBranch "
            . "&& git merge $sourceBranch "
            . "&& git push origin $targetBranch "
            . "&& git checkout $sourceBranch "
            . "&& git push origin $sourceBranch";
        if ($targetBranch == 'master') {
            $subBranches = ['dev', 'beta', 'demo'];
            foreach($subBranches as $branch) {
                $output .= "&& git branch -D $branch "
                    . "&& git checkout origin/$branch "
                    . "&& git checkout -b $branch "
                    . "&& git merge master "
                    . "&& git push origin $branch ";
            }
        }
        $output .= "\n";
        break;
    default:
        $output = 'command not found';
}

echo $output;