#!/bin/bash

# $rrd_dir $rrdfile1 $rrdfile2 $rrdfile3
#echo "<h3>OK $*</h3>"
#if [ $UID -eq 0 ]; then
#	su nobody -c "bash $0 $@"
#	exit 0
#fi
umask 022
V="$1"
shift
SRV="ALL"
declare -a DR
for i in "$@" ; do DR+=($i); done

XGRD1="MINUTE:3:MINUTE:6:MINUTE:12:0:0:%M"
XGRD4A="MINUTE:15:HOUR:1:HOUR:1:0:%H:%M"
XGRD4="MINUTE:15:HOUR:1:HOUR:2:0:%H:%M"
XGRD8="MINUTE:30:HOUR:2:HOUR:2:0:%Hh"
XGRD24="HOUR:1:HOUR:2:HOUR:8:0:%a %H:00"
XGRD48="HOUR:2:HOUR:4:HOUR:12:0:%a %H:00"
XGRD168="HOUR:8:HOUR:24:HOUR:24:0:%a"
XGRD1M="HOUR:12:DAY:1:DAY:1:0:%d"

COMMON="-w 670 -h 200 -i -l 0 -M -R normal -P"

declare -a LINES
#LINES=()
LINES+=("--font" "DEFAULT:10:Courier")
#CLRS=([0]="0000FF" [1]="007F00" [2]="FF0000" [3]="FFFF00" [4]="FF00FF" [5]="7Fff8F" [6]="FF7f40" [7]="1F7f40" [8]="ff1010" )
CLRS=([0]="0000FF" [2]="007F00" [1]="FF0000" [3]="FFFF00" [4]="FF00FF" [5]="7Fff8F" [6]="FF7f40" [7]="1F7f40" [8]="ff1010" )
# LAST file.rrd par1 ...
function std_def {
        local X="$1"
        local R="$2"
        local i
        shift 2
        for i in "$@"; do
                LINES+=("DEF:${i}=${R}:${i}:${X}")
        done
}
function std_def_x {
        local X="$1"
        local R="$2"
        local S="$3"
        local i
        shift 3
        for i in "$@"; do
                LINES+=("DEF:${i}${S}=${R}:${i}:${X}")
        done
}
function std_def_pair {
        local X="$1"
        local R="$2"
        local i
        shift 2
        local j
        j=""
        for i in "$@"; do
                if [ -z "$j" ]; then
                        j="$i"
                else
                        LINES+=("DEF:${j}=${R}:${i}:${X}")
                        j=""
                fi
        done
}

# suffix op par1 ...
function cdef_op {
        local S="$1"
        local O="$2"
        local i
        shift 2
        for i in "$@"; do
                LINES+=("CDEF:${i}${S}=${i},${O}")
        done
}
function std_def_km {
        local X="$1"
        local R="$2"
        shift 2
        std_def "$X" "$R" "$@"
        cdef_op "k" "1024,*" "$@"
        cdef_op "m" "1024,/" "$@"
}

function std_def_bit {
        local X="$1"
        local R="$2"
        shift 2
        std_def "$X" "$R" "$@"
        cdef_op "b" "8,*" "$@"
}

function std_hdr {
        echo '<html>'
        echo '<head><META HTTP-EQUIV="Refresh" CONTENT="'${1:-120}'">'
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> </head>'
}

function std_y_legend {
        LINES+=("-v" "$*")
}

function std_fonts {
        LINES+=("--font" "${1:-DEFAULT:10:Courier}")
}

std_y_legend "N"
std_fonts
LINES+=("-l 0")
LINES+=("-L 2")
LINES+=("-E")
I=1
S1='CDEF:MC=0'
S2='CDEF:LC=0'
for D in ${DR[@]}; do
  std_def_x "AVERAGE" "$D" $I MC LC
  S1="${S1},MC${I},+";
  S2="${S2},LC${I},+";
  I=$[$I+1]
done
LL=$[$I-1]

declare -a L1
for i in ${LINES[@]}; do L1+=($i); done
L1+=("--imginfo" "&nbsp;<IMG SRC=\"%s\" WIDTH=\"%lu\" HEIGHT=\"%lu\"/>")
L1+=($S1)

I=1
for D in ${DR[@]}; do
  j="AREA:MC${I}#${CLRS[$I]}40:"
  [ "$I" != "1" ] && j="$j:STACK"
  L1+=($j)
  I=$[$I+1]
done

L1+=("GPRINT:MC:LAST:Total meetings %3.0lf ")
I=1
for D in ${DR[@]}; do
  j="LINE1:MC${I}#${CLRS[$I]}:s$I"
  [ "$I" != "1" ] && j="$j:STACK"
  L1+=($j)
  j=''; [ "$I" = "$LL" ] && j="\l"
  L1+=("GPRINT:MC$I:LAST:%3.0lf${j}")
  I=$[$I+1]
done

declare -a L2
for i in ${LINES[@]}; do L2+=($i); done
L2+=("--imginfo" "&nbsp;<IMG SRC=\"%s\" WIDTH=\"%lu\" HEIGHT=\"%lu\"/>")
L2+=($S2)
L2+=("GPRINT:LC:LAST:Total users %3.0lf")

I=1
for D in ${DR[@]}; do
  j="AREA:LC${I}#${CLRS[$I]}40:"
  [ "$I" != "1" ] && j="$j:STACK"
  L2+=($j)
  I=$[$I+1]
done

I=1
for D in ${DR[@]}; do
  j="LINE1:LC${I}#${CLRS[$I]}:s$I"
  [ "$I" != "1" ] && j="$j:STACK"
  L2+=($j)
  j=''; [ "$I" = "$LL" ] && j="\l"
  L2+=("GPRINT:LC$I:LAST:%3.0lf${j}")
  I=$[$I+1]
done


#LINES+=("LINE2:MC#${CLRS[0]}:meetings\g")
#LINES+=("GPRINT:MC:LAST: %3.0lf")
#LINES+=("LINE:VM#${CLRS[2]}:Video(max)\g:dashes")
#LINES+=("GPRINT:VM:LAST: %3.0lf")
#LINES+=("LINE2:VC#${CLRS[6]}80:Video")
#LINES+=("GPRINT:VC:LAST:Video %3.0lf\l")

N="$SRV"
  std_hdr 120
  rrdtool graph "$V/srv${SRV}-h2.png" --start -$[4*3600] -t "$N 4 Hour" -x "$XGRD4A" $COMMON "${L1[@]}" 
  echo '<br>'
  rrdtool graph "$V/srv${SRV}-h8.png" --start -$[24*3600] -t "$N 24 Hour" -x "$XGRD8" $COMMON "${L1[@]}" 
  echo '<br>'
  rrdtool graph "$V/srv${SRV}-d.png" --start -$[7*24*3600] -t "$N 1 Week" -x "$XGRD168" $COMMON "${L1[@]}" 
  echo '<hr>'

  rrdtool graph "$V/srv${SRV}u-h2.png" --start -$[4*3600] -t "$N 4 Hour" -x "$XGRD4A" $COMMON "${L2[@]}" 
  echo '<br>'
  rrdtool graph "$V/srv${SRV}u-h8.png" --start -$[24*3600] -t "$N 24 Hour" -x "$XGRD8" $COMMON "${L2[@]}" 
  echo '<br>'
  rrdtool graph "$V/srv${SRV}u-d.png" --start -$[7*24*3600] -t "$N 1 Week" -x "$XGRD168" $COMMON "${L2[@]}" 
  echo '<br>'

  echo ' </html>'
exit 0
