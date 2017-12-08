# php-geohash

Simple GeoHash class for PHP

## Characteristic

Algorithmic processes clearly 

## Decimal - Base

|Decimal | 0 | 1 | 2 | 3 |  4 | 5 | 6 |  7| 8  | 9 | 10 | 11 | 12 | 13 | 14 | 15|
|--|--|--|--|--|--|--|--|--|--|--|--|--|--|--|--|--|
Base| 32|0|1|2|3|4|5|6|7|8|9|b|c|d|e|f|g


|Decimal|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|
--|--|--|--|--|--|--|--|--|--|--|--|--|--|--|--|--
|Base| 32|h|j|k|m|n|p|q|r|s|t|u|v|w|x|y|z|


## Errors

|geohash length | lat bits | lng bits | lat error | lng error | km error|
|--|--|--|--|--|--|
|1|2|3|±23|±23|±2500|
|2|5|5 |±2.8| ±5.6| ±630|
|3|7|8| ±0.70| ±0.70  |±78|
|4|10|10| ±0.087| ±0.18 | ±20|
|5|12|13| ±0.022| ±0.022|   ±2.4|
|6|15|15| ±0.0027| ±0.0055|   ±0.61|
|7|17|18| ±0.00068| ±0.00068|   ±0.076|
|8|20|20| ±0.000085| ±0.00017|   ±0.019|



## Ref

https://en.wikipedia.org/wiki/Geohash