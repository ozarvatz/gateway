/*
  +----------------------------------------------------------------------+
  | APC                                                                  |
  +----------------------------------------------------------------------+
  | Copyright (c) 2006 The PHP Group                                     |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Authors: Daniel Cowgill <dcowgill@communityconnect.com>              |
  |          George Schlossnagle <george@omniti.com>                     |
  |          Rasmus Lerdorf <rasmus@php.net>                             |
  |          Arun C. Murthy <arunc@yahoo-inc.com>                        |
  |          Gopal Vijayaraghavan <gopalv@yahoo-inc.com>                 |
  +----------------------------------------------------------------------+

   This software was contributed to PHP by Community Connect Inc. in 2002
   and revised in 2005 by Yahoo! Inc. to add support for PHP 5.1.
   Future revisions and derivatives of this source code must acknowledge
   Community Connect Inc. as the original contributor of this module by
   leaving this note intact in the source code.

   All other licensing and usage conditions are those of the PHP Group.

 */

/* $Id: apc_main.h,v 3.9 2007/02/28 01:15:18 gopalv Exp $ */

#ifndef APC_MAIN_H
#define APC_MAIN_H

/*
 * This module provides the primary interface between PHP and APC.
 */

extern int apc_module_init(int module_number TSRMLS_DC);
extern int apc_module_shutdown(TSRMLS_D);
extern int apc_process_init(int module_number TSRMLS_DC);
extern int apc_process_shutdown(TSRMLS_D);
extern int apc_request_init(TSRMLS_D);
extern int apc_request_shutdown(TSRMLS_D);

/*
 * apc_deactivate is called by the PHP interpreter when an "exception" is
 * raised (e.g., a call to the exit function) that unwinds the execution
 * stack.
 */
extern void apc_deactivate();


extern const char* apc_version();

#endif

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: expandtab sw=4 ts=4 sts=4 fdm=marker
 * vim<600: expandtab sw=4 ts=4 sts=4
 */
