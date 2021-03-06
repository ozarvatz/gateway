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
  | Authors: George Schlossnagle <george@omniti.com>                     |
  |          Rasmus Lerdorf <rasmus@php.net>                             |
  +----------------------------------------------------------------------+

   This software was contributed to PHP by Community Connect Inc. in 2002
   and revised in 2005 by Yahoo! Inc. to add support for PHP 5.1.
   Future revisions and derivatives of this source code must acknowledge
   Community Connect Inc. as the original contributor of this module by
   leaving this note intact in the source code.

   All other licensing and usage conditions are those of the PHP Group.

 */

/* $Id: apc_lock.h,v 3.20 2007/01/29 07:39:02 shire Exp $ */

#ifndef APC_LOCK
#define APC_LOCK

#include "apc_sem.h"
#include "apc_fcntl.h"
#include "apc_pthreadmutex.h"
#include "apc_futex.h"
#include "apc_spin.h"
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#ifdef TSRM_LOCKS
#define RDLOCK_AVAILABLE 0
#define NONBLOCKING_LOCK_AVAILABLE 0
/* quick & dirty: use TSRM mutex locks for now */
#define apc_lck_create(a,b,c,d) d=(int)tsrm_mutex_alloc()
#define apc_lck_destroy(a)    tsrm_mutex_free((MUTEX_T)a)
#define apc_lck_lock(a)       tsrm_mutex_lock((MUTEX_T)a)
#define apc_lck_rdlock(a)     tsrm_mutex_lock((MUTEX_T)a)
#define apc_lck_unlock(a)     tsrm_mutex_unlock((MUTEX_T)a)
#elif defined(APC_SEM_LOCKS)
#define RDLOCK_AVAILABLE 0
#define NONBLOCKING_LOCK_AVAILABLE 0
#define apc_lck_t int
#define apc_lck_create(a,b,c,d) d=apc_sem_create(NULL,(b),(c))
#define apc_lck_destroy(a)    apc_sem_destroy(a)
#define apc_lck_lock(a)       apc_sem_lock(a)
#define apc_lck_rdlock(a)     apc_sem_lock(a)
#define apc_lck_unlock(a)     apc_sem_unlock(a)
#elif defined(APC_PTHREADMUTEX_LOCKS)
#define RDLOCK_AVAILABLE 0
#define NONBLOCKING_LOCK_AVAILABLE 1
#define apc_lck_t pthread_mutex_t 
#define apc_lck_create(a,b,c,d) apc_pthreadmutex_create((pthread_mutex_t*)&d)
#define apc_lck_destroy(a)    apc_pthreadmutex_destroy(&a)
#define apc_lck_lock(a)       apc_pthreadmutex_lock(&a)
#define apc_lck_nb_lock(a)    apc_pthreadmutex_nonblocking_lock(&a)
#define apc_lck_rdlock(a)     apc_pthreadmutex_lock(&a)
#define apc_lck_unlock(a)     apc_pthreadmutex_unlock(&a)
#elif defined(APC_FUTEX_LOCKS)
#define NONBLOCKING_LOCK_AVAILABLE 1 
#define apc_lck_t int 
#define apc_lck_create(a,b,c,d) d=apc_futex_create()
#define apc_lck_destroy(a)    apc_futex_destroy(&a)
#define apc_lck_lock(a)       apc_futex_lock(&a)
#define apc_lck_nb_lock(a)    apc_futex_nonblocking_lock(&a)
#define apc_lck_rdlock(a)     apc_futex_lock(&a)
#define apc_lck_unlock(a)     apc_futex_unlock(&a)
#elif defined(APC_SPIN_LOCKS)
#define NONBLOCKING_LOCK_AVAILABLE APC_SLOCK_NONBLOCKING_LOCK_AVAILABLE
#define apc_lck_t slock_t 
#define apc_lck_create(a,b,c,d) apc_slock_create((slock_t*)&(d))
#define apc_lck_destroy(a)    apc_slock_destroy(&a)
#define apc_lck_lock(a)       apc_slock_lock(&a)
#define apc_lck_nb_lock(a)    apc_slock_nonblocking_lock(&a)
#define apc_lck_rdlock(a)     apc_slock_lock(&a)
#define apc_lck_unlock(a)     apc_slock_unlock(&a)
#else
#define RDLOCK_AVAILABLE 1
#ifdef PHP_WIN32
#define NONBLOCKING_LOCK_AVAILABLE 0
#else
#define NONBLOCKING_LOCK_AVAILABLE 1
#endif
#define apc_lck_t int
#define apc_lck_create(a,b,c,d) d=apc_fcntl_create((a))
#define apc_lck_destroy(a)    apc_fcntl_destroy(a)
#define apc_lck_lock(a)       apc_fcntl_lock(a)
#define apc_lck_nb_lock(a)    apc_fcntl_nonblocking_lock(a)
#define apc_lck_rdlock(a)     apc_fcntl_rdlock(a)
#define apc_lck_unlock(a)     apc_fcntl_unlock(a)
#endif

#endif
