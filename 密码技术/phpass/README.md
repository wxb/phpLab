# Portable PHP password hashing framework

phpass (pronounced "pH pass") is a portable public domain password hashing framework for use in PHP applications. It is meant to work with PHP 3 and above, and it has actually been tested with at least PHP 3.0.18 through 5.4.x so far. (PHP 3 support is likely to be dropped in next revision.)

The preferred (most secure) hashing method supported by phpass is the OpenBSD-style Blowfish-based bcrypt, also supported with our public domain crypt_blowfish package (for C applications), and known in PHP as CRYPT_BLOWFISH, with a fallback to BSDI-style extended DES-based hashes, known in PHP as CRYPT_EXT_DES, and a last resort fallback to MD5-based salted and variable iteration count password hashes implemented in phpass itself (also referred to as portable hashes).

To ensure that the fallbacks will never occur, PHP 5.3.0+ or the Suhosin patch may be used. PHP 5.3.0+ and Suhosin integrate crypt_blowfish into the PHP interpreter such that bcrypt is available for use by PHP scripts even if the host system lacks support for it.

Included in the package are a PHP source file implementing the PasswordHash PHP class, a tiny PHP application demonstrating the use of the PasswordHash class, and a C reimplementation of the portable hashes (used for testing correctness of the primary implementation only).

There's a lengthy article/tutorial on introducing password hashing with phpass into a PHP application, as well as on other aspects of managing users and passwords. This article along with sample programs referenced from it is also available for download below. Some of you might prefer this much shorter third-party article focusing solely on introducing phpass into a PHP application. Finally, also relevant is our presentation on the history of password security.

[detail](http://www.openwall.com/phpass/)
