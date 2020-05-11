AKikhaev CliRemainCalc

* Calculate and show processing and remain time of any process
* Version: 1.0

##### Using way
    $calc = new CliRemainCalc( < array | instance of Countable | int > );
    foreach (.. as &$item) {
        <any processing>
        
        $calc->plot();
    }

##### The result
* Detailed automatic updating calculation print of remain and elapsed time 
* Calculation producing only one time in 5 sec or seldom 
* Shows at last line of cli output and at title of terminal window 

##### Example

* 15% Updating... 2343/15600 0:01:27 0:09:40

##### Requirements
* PHP 5+

##### installation
* composer.json:
```
    {
        "repositories": [
            {
                "url": "https://github.com/AKikhaev/CliRemainCalc.git",
                "type": "vcs"
            }
        ],
        "require": {
            "akikhaev/cli-remain-calc": "@dev"
        }
    }
```
`composer install`