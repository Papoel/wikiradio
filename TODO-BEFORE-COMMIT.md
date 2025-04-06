# TODO avant de commit

Ce fichier a été généré automatiquement par `make before-commit` le 06-04-2025 à 14h19.

## Liste des problèmes à corriger

### Problèmes détectés par PHPStan


#### [Command/CleanRadiogrammeErrorsCommand.php](src/Command/CleanRadiogrammeErrorsCommand.php)

- [ ] [**Ligne 56**](src/Command/CleanRadiogrammeErrorsCommand.php#L56): Cannot cast mixed to int.                            

- [ ] [**Ligne 115**](src/Command/CleanRadiogrammeErrorsCommand.php#L115): Parameter #2 ...$values of function sprintf expects  


#### [Command/ExportRadiogrammeErrorsCommand.php](src/Command/ExportRadiogrammeErrorsCommand.php)

- [ ] [**Ligne 61**](src/Command/ExportRadiogrammeErrorsCommand.php#L61): Parameter #1 $path of function dirname expects string, mixed given.    

- [ ] [**Ligne 68**](src/Command/ExportRadiogrammeErrorsCommand.php#L68): Parameter #1 $errorType of method                                      

- [ ] [**Ligne 69**](src/Command/ExportRadiogrammeErrorsCommand.php#L69): Parameter #2 ...$values of function sprintf expects                    

- [ ] [**Ligne 81**](src/Command/ExportRadiogrammeErrorsCommand.php#L81): Parameter #1 $path of static method                                    

- [ ] [**Ligne 154**](src/Command/ExportRadiogrammeErrorsCommand.php#L154): Parameter #3 ...$values of function sprintf expects                    


#### [Entity/Radiogramme.php](src/Entity/Radiogramme.php)

- [ ] [**Ligne 375**](src/Entity/Radiogramme.php#L375): Cannot call method format() on DateTimeInterface|null.  


#### [Repository/RadiogrammeErrorRepository.php](src/Repository/RadiogrammeErrorRepository.php)

- [ ] [**Ligne 31**](src/Repository/RadiogrammeErrorRepository.php#L31): Method App\Repository\RadiogrammeErrorRepository::findUnresolved()    

- [ ] [**Ligne 44**](src/Repository/RadiogrammeErrorRepository.php#L44): Method                                                                

- [ ] [**Ligne 60**](src/Repository/RadiogrammeErrorRepository.php#L60): Method App\Repository\RadiogrammeErrorRepository::findByErrorType()   


#### [Services/ImportDataService.php](src/Services/ImportDataService.php)

- [ ] [**Ligne 19**](src/Services/ImportDataService.php#L19): Property App\Services\ImportDataService::$errorRecords type has no                    

- [ ] [**Ligne 20**](src/Services/ImportDataService.php#L20): Property App\Services\ImportDataService::$errorTypes type has no                      

- [ ] [**Ligne 27**](src/Services/ImportDataService.php#L27): Property App\Services\ImportDataService::$entityManager                               

- [ ] [**Ligne 71**](src/Services/ImportDataService.php#L71): Access to an undefined property                                                       

- [ ] [**Ligne 236**](src/Services/ImportDataService.php#L236): Method App\Services\ImportDataService::processBatch() is unused.                      

- [ ] [**Ligne 236**](src/Services/ImportDataService.php#L236): Method App\Services\ImportDataService::processBatch() return type has                 

- [ ] [**Ligne 251**](src/Services/ImportDataService.php#L251): Method App\Services\ImportDataService::isEntityManagerOpen() is                       

- [ ] [**Ligne 253**](src/Services/ImportDataService.php#L253): Negated boolean expression is always false.                                           

- [ ] [**Ligne 274**](src/Services/ImportDataService.php#L274): Property App\Services\ImportDataService::$entityManager                               

- [ ] [**Ligne 277**](src/Services/ImportDataService.php#L277): Property App\Services\ImportDataService::$entityManager                               

- [ ] [**Ligne 284**](src/Services/ImportDataService.php#L284): Method                                                                                

- [ ] [**Ligne 311**](src/Services/ImportDataService.php#L311): Method App\Services\ImportDataService::handleImportError() has                        

- [ ] [**Ligne 347**](src/Services/ImportDataService.php#L347): Method App\Services\ImportDataService::countTotalRecords() has                        

- [ ] [**Ligne 374**](src/Services/ImportDataService.php#L374): Method App\Services\ImportDataService::createOrUpdateRadiogramme()                    

- [ ] [**Ligne 613**](src/Services/ImportDataService.php#L613): Method App\Services\ImportDataService::formatRecordForErrorMessage()                  

- [ ] [**Ligne 613**](src/Services/ImportDataService.php#L613): Method App\Services\ImportDataService::formatRecordForErrorMessage()                  

### Problèmes détectés par Psalm



### Problèmes détectés par PHPMD

- [ ] [**Command/CleanRadiogrammeErrorsCommand.php:60**](src/Command/CleanRadiogrammeErrorsCommand.php#L60): MissingImport - Missing class import via use statement (line '60', column '26').
- [ ] [**Command/CleanRadiogrammeErrorsCommand.php:64**](src/Command/CleanRadiogrammeErrorsCommand.php#L64): ShortVariable - Avoid variables with short names like $qb. Configured minimum length is 3.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:21**](src/Command/ExportRadiogrammeErrorsCommand.php#L21): LongVariable - Avoid excessively long variable names like $radiogrammeErrorRepository. Keep variable name length under 20.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:50**](src/Command/ExportRadiogrammeErrorsCommand.php#L50): CyclomaticComplexity - The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:50**](src/Command/ExportRadiogrammeErrorsCommand.php#L50): NPathComplexity - The method execute() has an NPath complexity of 528. The configured NPath complexity threshold is 200.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:50**](src/Command/ExportRadiogrammeErrorsCommand.php#L50): ExcessiveMethodLength - The method execute() has 114 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:70**](src/Command/ExportRadiogrammeErrorsCommand.php#L70): ElseExpression - The method execute uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:81**](src/Command/ExportRadiogrammeErrorsCommand.php#L81): StaticAccess - Avoid using static access to class '\League\Csv\Writer' in method 'execute'.
- [ ] [**Entity/Radiogramme.php:12**](src/Entity/Radiogramme.php#L12): ExcessivePublicCount - The class Radiogramme has 49 public methods and attributes. Consider reducing the number of public items to less than 45.
- [ ] [**Entity/Radiogramme.php:12**](src/Entity/Radiogramme.php#L12): TooManyFields - The class Radiogramme has 24 fields. Consider redesigning Radiogramme to keep the number of fields under 15.
- [ ] [**Entity/Radiogramme.php:17**](src/Entity/Radiogramme.php#L17): ShortVariable - Avoid variables with short names like $id. Configured minimum length is 3.
- [ ] [**Entity/Radiogramme.php:32**](src/Entity/Radiogramme.php#L32): ShortVariable - Avoid variables with short names like $pp. Configured minimum length is 3.
- [ ] [**Entity/Radiogramme.php:56**](src/Entity/Radiogramme.php#L56): ShortVariable - Avoid variables with short names like $oi. Configured minimum length is 3.
- [ ] [**Entity/Radiogramme.php:62**](src/Entity/Radiogramme.php#L62): ShortVariable - Avoid variables with short names like $rf. Configured minimum length is 3.
- [ ] [**Entity/Radiogramme.php:146**](src/Entity/Radiogramme.php#L146): ShortVariable - Avoid variables with short names like $pp. Configured minimum length is 3.
- [ ] [**Entity/Radiogramme.php:242**](src/Entity/Radiogramme.php#L242): ShortVariable - Avoid variables with short names like $oi. Configured minimum length is 3.
- [ ] [**Entity/Radiogramme.php:266**](src/Entity/Radiogramme.php#L266): ShortVariable - Avoid variables with short names like $rf. Configured minimum length is 3.
- [ ] [**Entity/RadiogrammeError.php:12**](src/Entity/RadiogrammeError.php#L12): ExcessivePublicCount - The class RadiogrammeError has 58 public methods and attributes. Consider reducing the number of public items to less than 45.
- [ ] [**Entity/RadiogrammeError.php:12**](src/Entity/RadiogrammeError.php#L12): TooManyFields - The class RadiogrammeError has 28 fields. Consider redesigning RadiogrammeError to keep the number of fields under 15.
- [ ] [**Entity/RadiogrammeError.php:12**](src/Entity/RadiogrammeError.php#L12): ExcessiveClassComplexity - The class RadiogrammeError has an overall complexity of 59 which is very high. The configured complexity threshold is 50.
- [ ] [**Entity/RadiogrammeError.php:17**](src/Entity/RadiogrammeError.php#L17): ShortVariable - Avoid variables with short names like $id. Configured minimum length is 3.
- [ ] [**Entity/RadiogrammeError.php:53**](src/Entity/RadiogrammeError.php#L53): ShortVariable - Avoid variables with short names like $oi. Configured minimum length is 3.
- [ ] [**Entity/RadiogrammeError.php:59**](src/Entity/RadiogrammeError.php#L59): ShortVariable - Avoid variables with short names like $rf. Configured minimum length is 3.
- [ ] [**Entity/RadiogrammeError.php:102**](src/Entity/RadiogrammeError.php#L102): MissingImport - Missing class import via use statement (line '102', column '32').
- [ ] [**Entity/RadiogrammeError.php:247**](src/Entity/RadiogrammeError.php#L247): ShortVariable - Avoid variables with short names like $oi. Configured minimum length is 3.
- [ ] [**Entity/RadiogrammeError.php:271**](src/Entity/RadiogrammeError.php#L271): ShortVariable - Avoid variables with short names like $rf. Configured minimum length is 3.
- [ ] [**Entity/RadiogrammeError.php:453**](src/Entity/RadiogrammeError.php#L453): MissingImport - Missing class import via use statement (line '453', column '32').
- [ ] [**Services/ImportDataService.php:16**](src/Services/ImportDataService.php#L16): ExcessiveClassComplexity - The class ImportDataService has an overall complexity of 66 which is very high. The configured complexity threshold is 50.
- [ ] [**Services/ImportDataService.php:24**](src/Services/ImportDataService.php#L24): LongVariable - Avoid excessively long variable names like $radiogrammeRepository. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:25**](src/Services/ImportDataService.php#L25): LongVariable - Avoid excessively long variable names like $radiogrammeErrorRepository. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:30**](src/Services/ImportDataService.php#L30): CyclomaticComplexity - The method importData() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- [ ] [**Services/ImportDataService.php:30**](src/Services/ImportDataService.php#L30): ExcessiveMethodLength - The method importData() has 201 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Services/ImportDataService.php:74**](src/Services/ImportDataService.php#L74): LongVariable - Avoid excessively long variable names like $processedUniqueValues. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:77**](src/Services/ImportDataService.php#L77): LongVariable - Avoid excessively long variable names like $successfullyImportedValues. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:121**](src/Services/ImportDataService.php#L121): MissingImport - Missing class import via use statement (line '121', column '33').
- [ ] [**Services/ImportDataService.php:147**](src/Services/ImportDataService.php#L147): ElseExpression - The method importData uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:179**](src/Services/ImportDataService.php#L179): LongVariable - Avoid excessively long variable names like $successfullyImportedCount. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:236**](src/Services/ImportDataService.php#L236): UnusedPrivateMethod - Avoid unused private methods such as 'processBatch'.
- [ ] [**Services/ImportDataService.php:236**](src/Services/ImportDataService.php#L236): UnusedFormalParameter - Avoid unused parameters such as '$progressBar'.
- [ ] [**Services/ImportDataService.php:251**](src/Services/ImportDataService.php#L251): UnusedPrivateMethod - Avoid unused private methods such as 'isEntityManagerOpen'.
- [ ] [**Services/ImportDataService.php:290**](src/Services/ImportDataService.php#L290): MissingImport - Missing class import via use statement (line '290', column '29').
- [ ] [**Services/ImportDataService.php:311**](src/Services/ImportDataService.php#L311): ShortVariable - Avoid variables with short names like $e. Configured minimum length is 3.
- [ ] [**Services/ImportDataService.php:386**](src/Services/ImportDataService.php#L386): ElseExpression - The method createOrUpdateRadiogramme uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:411**](src/Services/ImportDataService.php#L411): MissingImport - Missing class import via use statement (line '411', column '29').
- [ ] [**Services/ImportDataService.php:428**](src/Services/ImportDataService.php#L428): ElseExpression - The method createOrUpdateRadiogramme uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:451**](src/Services/ImportDataService.php#L451): ElseExpression - The method getExecutionTime uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:462**](src/Services/ImportDataService.php#L462): CyclomaticComplexity - The method persistErrors() has a Cyclomatic Complexity of 23. The configured cyclomatic complexity threshold is 10.
- [ ] [**Services/ImportDataService.php:462**](src/Services/ImportDataService.php#L462): NPathComplexity - The method persistErrors() has an NPath complexity of 24582. The configured NPath complexity threshold is 200.
- [ ] [**Services/ImportDataService.php:462**](src/Services/ImportDataService.php#L462): ExcessiveMethodLength - The method persistErrors() has 147 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Services/ImportDataService.php:479**](src/Services/ImportDataService.php#L479): LongVariable - Avoid excessively long variable names like $processedUniqueValues. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:482**](src/Services/ImportDataService.php#L482): UnusedLocalVariable - Avoid unused local variables such as '$index'.
- [ ] [**Services/ImportDataService.php:506**](src/Services/ImportDataService.php#L506): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:516**](src/Services/ImportDataService.php#L516): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:528**](src/Services/ImportDataService.php#L528): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:538**](src/Services/ImportDataService.php#L538): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:553**](src/Services/ImportDataService.php#L553): MissingImport - Missing class import via use statement (line '553', column '37').
- [ ] [**Services/ImportDataService.php:570**](src/Services/ImportDataService.php#L570): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:595**](src/Services/ImportDataService.php#L595): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:613**](src/Services/ImportDataService.php#L613): UnusedPrivateMethod - Avoid unused private methods such as 'formatRecordForErrorMessage'.
### Code dupliqué détecté par PHPCPD

- [ ] **Duplication entre fichiers** :

  ```
Found 3 clones with 296 duplicated lines in 2 files:

  - [src/Entity/Radiogramme.php:150-370 (220 lines)](src/Entity/Radiogramme.php#L150-L370)
  - [src/Entity/Radiogramme.php:88-142 (54 lines)](src/Entity/Radiogramme.php#L88-L142)
  - [src/Entity/Radiogramme.php:44-66 (22 lines)](src/Entity/Radiogramme.php#L44-L66)
  ```


