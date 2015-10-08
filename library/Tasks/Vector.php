<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Tasks;

class Vector extends Tasks {
    public function run(\Config $config) {
        $stats = gremlin_queryColumn(<<<GREMLIN
g.idx('atoms')[['atom':'Foreach']]
.sideEffect{
    ForeachControlKeyAssignement = it.out('VALUE').has('atom', 'Keyvalue').count();
    if (it.out('VALUE').has('atom', 'Keyvalue').out('KEY').any()) {
        key = it.out('VALUE').has('atom', 'Keyvalue').out('KEY').next().code;
    } else {
        key = 'no key';
    }
}
.sideEffect{
    source = it.out('SOURCE').next().code;
    ForeachCommandArrayNumber = it.out('BLOCK').out.loop(1){true}{it.object.code == source}.count();
}
.sideEffect{
    ForeachCommandKeyArrayNumber = it.out('BLOCK').out.loop(1){true}{it.object.code == key}.count();
    IsKeyIndex = it.out('BLOCK').out.loop(1){true}{it.object.code == key}.filter{ it.inE('INDEX').any()}.count();
}
.sideEffect{
    IsUnset = it.out('BLOCK').out.loop(1){true}{it.object.token == 'T_UNSET'}.out('ARGUMENTS').out.loop(1){true}{it.object.code == source}.count();
    IsKeyModify = it.out('BLOCK').out.loop(1){true}{it.object.code == key}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Variables\\\\IsModified').any()}.count();
}
.sideEffect{
    if (it.in.loop(1){true}{it.object.token == 'T_FILENAME'}.any()) {
        theFile = it.in.loop(1){true}{it.object.token == 'T_FILENAME'}.next().fullcode;
    } else {
        theFile = 'no file';
    }
}

.transform{ ['ForeachControlStructureExist':it.id,
             'ForeachControlKeyAssignement':ForeachControlKeyAssignement,
             'ForeachCommandArrayNumber':ForeachCommandArrayNumber,
             'ForeachCommandKeyArrayNumber':ForeachCommandKeyArrayNumber,
             'IsKeyIndex':IsKeyIndex,
             'IsUnset':IsUnset,
             'IsKeyModify':IsKeyModify,
             'File':theFile,
             'Line':it.line];}

GREMLIN
);
        
        $fp = fopen('vector.csv', 'w+');
        foreach($stats as $stat) {
            fputcsv($fp, (array) $stat);
        }
        fclose($fp);
        
        print count($stats)." rows found\n";
    }
}

?>
