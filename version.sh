#!/bin/bash

CURRENTDIR=`pwd`
MAINFILE="couverts.php" # this should be the name of your main php file in the wordpress plugin

# git config
GITPATH="$CURRENTDIR/" # this file should be in the base of your git repository


NEWVERSION1=`grep "^Stable tag:" $GITPATH/README.txt | awk -F' ' '{print $NF}'`
echo "README.txt version: $NEWVERSION1"
NEWVERSION2=`grep "Version:" $GITPATH/$MAINFILE | awk -F' ' '{print $NF}'`
echo "$MAINFILE version: $NEWVERSION2"

if [ "$NEWVERSION1" != "$NEWVERSION2" ]; then echo "Version in README.txt & $MAINFILE don't match. Exiting...."; exit 1; fi

echo "Versions match in README.txt and $MAINFILE. Let's proceed..."

read -p "Current version is $NEWVERSION1. Enter new version: " NEXTVERSION

echo "Setting to $NEXTVERSION"

sed -i.bak "s/Stable tag: ${NEWVERSION1}/Stable tag: ${NEXTVERSION}/g" ${GITPATH}/README.txt
sed -i.bak "s/Version:            ${NEWVERSION1}/Version:            ${NEXTVERSION}/g" ${GITPATH}/${MAINFILE}

echo "= ${NEXTVERSION} =" > .versioncomment-$NEXTVERSION
vi .versioncomment-$NEXTVERSION
echo >> README.txt
cat .versioncomment-$NEXTVERSION >> README.txt
echo >> README.txt
rm .versioncomment-$NEXTVERSION

rm couverts.php.bak
rm README.txt.bak