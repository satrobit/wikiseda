

#  wikiseda
A simple tool to download a signers whole archive from wikiseda.com

# Installation

Install via [Composer](https://getcomposer.org/download/):
~~~bash
composer global require satrobit/wikiseda
~~~

# Usage

## Search
**Command:**
~~~bash
wikiseda search shadmehr
~~~
**Arguments:**
|name|Description|
|--|--|
|query|Search Query|

**Options:**
|name|Description|Default Value|
|--|--|--|
|type|Type of the result|artist|
|page|Custom result page|0|
|order|Custom result order|top|

**Result:**

~~~
+-------+-----------------+--------+--------+
| ID    | Name            | Albums | Tracks |
+-------+-----------------+--------+--------+
| 76    | Shadmehr        | 26     | 970    |
| 13581 | Shadmehr Aghili | 0      | 3      |
| 12506 | Shadmehr Nasiri | 0      | 2      |
| 14824 | Shadmehr Jafari | 0      | 3      |
| 3793  | Shadmehr Jalali | 0      | 11     |
| 7857  | Shadmehr Barati | 0      | 1      |
| 22879 | Mohsen Shadmehr | 0      | 4      |
| 26192 | Shadmehr Jamali | 0      | 1      |
+-------+-----------------+--------+--------+
~~~


## Artists archive
**Command:**
~~~bash
wikiseda artist 76
~~~
**Arguments:**
|name|Description|
|--|--|
|artist|Artist ID|

**Options:**
|name|Description|Default Value|
|--|--|--|
|type|Type of the result|album|
|page|Custom result page|0|
|order|Custom result order|top|

**Result:**

~~~
+-------+--------------------------+-------+------------+
| ID    | Album                    | Type  | Date       |
+-------+--------------------------+-------+------------+
| 22707 | Tasvir                   | album | 2018-02-19 |
| 21861 | Tajrobeh Kon             | album | 2016-12-19 |
| 5811  | Tarafdar                 | album | 2012-07-22 |
| 571   | Live In Concert Dubai    | album | 2011-03-24 |
| 487   | Antique                  | album | 2010-03-19 |
| 230   | Taghdir                  | album | 2009-03-20 |
| 231   | Sebab                    | album | 2008-03-19 |
| 484   | Pop Corn                 | album | 2006-03-19 |
| 488   | Talafi                   | album | 2005-03-19 |
| 485   | Adam Forosh              | album | 2004-03-18 |
| 476   | Dori Va Pashimani        | album | 2003-03-19 |
| 5921  | Naghmehaye Mashreghi     | album | 2002-03-19 |
| 497   | Khiali Nist              | album | 2002-03-19 |
| 481   | Adamo Hava               | album | 2001-03-19 |
| 2930  | Mashgh Sokoot            | album | 2000-03-18 |
| 486   | Pare Parvaz              | album | 2000-03-18 |
| 483   | Fariba                   | album | 2000-03-18 |
| 479   | Dehati                   | album | 1999-03-19 |
| 478   | Mosafer                  | album | 1998-03-19 |
| 482   | Bahare Man               | album | 1997-03-19 |
| 20202 | Remix                    | album | 2014-09-24 |
| 6361  | VERJEN PIANO ALBUM PIANO | album | 2012-08-01 |
| 5920  | Albume Moshtarek         | album | 1998-03-19 |
| 9869  | Rage Khaab               | album |            |
| 2929  | Sham Ghariban            | album |            |
+-------+--------------------------+-------+------------+
~~~

## Download (the fun part)
**Command:**
~~~bash
wikiseda download 76
~~~
**Arguments:**
|name|Description|
|--|--|
|artist|Artist ID|

**Options:**
|name|Description|Default Value|
|--|--|--|
|output|Directory to store music files|output/|
|concurrent|Number of concurrent workers|4|

**Result:**
~~~
486/486 [============================]   100%
~~~

## Note
There are a few options on each command which helps you to tailor this tool to your needs. Use the `--help` option to find out about them.
## License

This project is released under the [MIT](https://github.com/satrobit/wikiseda/blob/master/LICENSE) License.
