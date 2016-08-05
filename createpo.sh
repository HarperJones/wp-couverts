#!/bin/bash

TMPFILELIST=/tmp/xgettext_filelist.txt
#LANGFILE=web/app/languages/themes/roots.po
LANGFILE=languages/couverts-nl_NL.po
#LANGFILE=roots.po
#KEYWORDS=__:2,_e:2,_x
KEYWORDS="__ _e _x _n _ex _nx translate"

find src -iname "*.php" > $TMPFILELIST
find templates -name "*.php" >> $TMPFILELIST

cat $TMPFILELIST > ${TMPFILELIST}.new
mv ${TMPFILELIST}.new ${TMPFILELIST}

if [ ! -f $LANGFILE ]; then
	touch $LANGFILE
fi

for KEYWORD in `echo $KEYWORDS`; do
	if [ ! -f xgettext_${KEYWORD}.po ]; then
		touch xgettext_${KEYWORD}.po
	fi

	# new template
	xgettext -L php --from-code=utf-8  -d roots  -f $TMPFILELIST --keyword=$KEYWORD -o xgettext_${KEYWORD}.po

	# update template
	xgettext -L php --from-code=utf-8 -d roots -j -f $TMPFILELIST --keyword=$KEYWORD -o xgettext_${KEYWORD}.po

	sed --in-place xgettext_${KEYWORD}.po --expression=s/CHARSET/UTF-8/
done

BOO=
for KEYWORD in `echo $KEYWORDS`; do
	BOO="${BOO} xgettext_${KEYWORD}.po"
done
msgcat $BOO -o ${LANGFILE}.new

if [ ! -f $LANGFILE ]; then
	mv ${LANGFILE}.new ${LANGFILE}
else 
	msgmerge --update --backup=numbered $LANGFILE ${LANGFILE}.new
	rm ${LANGFILE}.new
fi
rm $BOO

