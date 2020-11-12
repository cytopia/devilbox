#!/bin/bash
# This script will synchronize language file with the master 
# english translation using diff(1) utility.
# It doesn't translate strings, only inserts english versions
# to proper positions and deletes removed. And it doesn't 
# synchronize commented lines. 
# You need to have GNU ed and diff installed in $PATH.
# 
# Usage: synch <language>
#
#   <language> is the filename without the .php extension
#
# BTW, diff should create better ed scripts than previous 
# version of synch (that one with awk code). If it will not, 
# be frightened about patching Linux kernel sources ;-)

if [ -z $1 ] ; then
	echo "You must tell me which language I should synchronize."
	echo -e "for example: \n\t$0 polish"
	exit
fi

if [ ! -f $1.php ] ; then
	echo "Sorry, I cannot find $1.php"
	exit
fi

echo "Making backup of $1.php"
cp $1.php $1.php.old

# Find variables names ( "$lang['strfoo']" part )
cat english.php | awk -F"=" '{print $1}' > english.tmp
cat $1.php | awk -F"=" '{print $1}' > $1.tmp

# diff variable names and create ed script
diff --ignore-case --ignore-all-space --ignore-blank-lines \
	--ignore-matching-lines="*" \
	--ignore-matching-lines="[^:]//" \
	--ed \
	$1.tmp english.tmp > diff.ed

# No more need for .tmp files
rm *.tmp

# Add english values and ed destination file
cat diff.ed | awk '
function grep_n(what, where,   n, ln) {
# Returns line with searched text

    while ( (getline line < where ) > 0 ) {
	if (index(line, what)>0) {
	    gsub("^\t","",what);
	    split(line,a,"=");
	    print what" = "a[2];
    }
}
	close(where);
}

BEGIN	{ FS="=" }

/\$lang/{ grep_n($1, "english.php") ;
	  next;	}

	{ print	}
END	{ print "w" }' \
| ed $1.php

# Clean temporary files
rm diff.ed

