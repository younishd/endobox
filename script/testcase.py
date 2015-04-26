#!/usr/bin/env python

##
## This file is part of endobox.
##
## (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
##
## For the full copyright and license information, please view the LICENSE
## file that was distributed with this source code.
##

import os
import sys

TEST_DIR = os.path.dirname(os.path.abspath(__file__)) + '/../tests/'

TEMPLATE = """\
<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class {} extends PHPUnit_Framework_TestCase {{

    test_foobar()
    {{
        //
    }}

}}
"""

USAGE = """\
testcase

    script/testcase.py <ClassName> [...]

I use this script to create new PHPUnit test case files for endobox classes.

Copyright (c) 2015 - YouniS Bensalah <younis.bensalah@riseup.net>
"""

def main():
    if len(sys.argv) < 2:
        print(USAGE)
        sys.exit(-1)
        
    if not os.path.exists(TEST_DIR):
        print("Test directory '{}' does not exist.".format(TEST_DIR))
        sys.exit(-1)
        
    for i in range(1, len(sys.argv)):
        class_name = sys.argv[i]
        test_class_name = class_name + 'Test'
        test_file_name = test_class_name + '.php'
        test_file_path = TEST_DIR + test_file_name
        if os.path.isfile(test_file_path):
            print("Skip existing test case '{}'.".format(test_file_name))
            break
        print("Create test case '{}'.".format(test_file_name))
        test_file = open(test_file_path, 'w+')
        code = TEMPLATE.format(test_class_name)
        test_file.write(code)
        test_file.close()
    
if __name__ == '__main__':
    main()
