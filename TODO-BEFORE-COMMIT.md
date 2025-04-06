# TODO avant de commit

Ce fichier a été généré automatiquement par `make before-commit` le 06-04-2025 à 18h24.

## Liste des problèmes à corriger

### Problèmes de style de code (PSR-12)

- [ ] FOUND 2 ERRORS AFFECTING 2 LINES
- [ ]  415 | ERROR | Expected 1 space after closing brace; newline found
- [ ]  426 | ERROR | Expected 1 space after closing brace; newline found


### Problèmes détectés par PHPStan


#### [Command/CleanRadiogrammeErrorsCommand.php](src/Command/CleanRadiogrammeErrorsCommand.php)

- [ ] [**Ligne 67**](src/Command/CleanRadiogrammeErrorsCommand.php#L67): Cannot cast mixed to int.                                                             

- [ ] [**Ligne 110**](src/Command/CleanRadiogrammeErrorsCommand.php#L110): Parameter #2 $errors of method                                                        

- [ ] [**Ligne 142**](src/Command/CleanRadiogrammeErrorsCommand.php#L142): Parameter #2 ...$values of function sprintf expects                                   

- [ ] [**Ligne 150**](src/Command/CleanRadiogrammeErrorsCommand.php#L150): Method                                                                                

- [ ] [**Ligne 171**](src/Command/CleanRadiogrammeErrorsCommand.php#L171): Cannot call method format() on DateTimeInterface|null.                                

- [ ] [**Ligne 194**](src/Command/CleanRadiogrammeErrorsCommand.php#L194): Method                                                                                


#### [Command/ExportRadiogrammeErrorsCommand.php](src/Command/ExportRadiogrammeErrorsCommand.php)

- [ ] [**Ligne 61**](src/Command/ExportRadiogrammeErrorsCommand.php#L61): Parameter #1 $path of function dirname expects string, mixed given.    

- [ ] [**Ligne 68**](src/Command/ExportRadiogrammeErrorsCommand.php#L68): Parameter #1 $errorType of method                                      

- [ ] [**Ligne 69**](src/Command/ExportRadiogrammeErrorsCommand.php#L69): Parameter #2 ...$values of function sprintf expects                    

- [ ] [**Ligne 81**](src/Command/ExportRadiogrammeErrorsCommand.php#L81): Parameter #1 $path of static method                                    

- [ ] [**Ligne 154**](src/Command/ExportRadiogrammeErrorsCommand.php#L154): Parameter #3 ...$values of function sprintf expects                    


#### [Repository/RadiogrammeErrorRepository.php](src/Repository/RadiogrammeErrorRepository.php)

- [ ] [**Ligne 31**](src/Repository/RadiogrammeErrorRepository.php#L31): Method App\Repository\RadiogrammeErrorRepository::findUnresolved()    

- [ ] [**Ligne 44**](src/Repository/RadiogrammeErrorRepository.php#L44): Method                                                                

- [ ] [**Ligne 60**](src/Repository/RadiogrammeErrorRepository.php#L60): Method App\Repository\RadiogrammeErrorRepository::findByErrorType()   


#### [Services/ImportDataService.php](src/Services/ImportDataService.php)

- [ ] [**Ligne 19**](src/Services/ImportDataService.php#L19): Property App\Services\ImportDataService::$errorRecords type has no                    

- [ ] [**Ligne 20**](src/Services/ImportDataService.php#L20): Property App\Services\ImportDataService::$errorTypes type has no                      

- [ ] [**Ligne 27**](src/Services/ImportDataService.php#L27): Property App\Services\ImportDataService::$entityManager                               

- [ ] [**Ligne 30**](src/Services/ImportDataService.php#L30): Method App\Services\ImportDataService::importData() has parameter                     

- [ ] [**Ligne 228**](src/Services/ImportDataService.php#L228): Method App\Services\ImportDataService::processBatch() is unused.                      

- [ ] [**Ligne 228**](src/Services/ImportDataService.php#L228): Method App\Services\ImportDataService::processBatch() return type has                 

- [ ] [**Ligne 247**](src/Services/ImportDataService.php#L247): Negated boolean expression is always false.                                           

- [ ] [**Ligne 268**](src/Services/ImportDataService.php#L268): Property App\Services\ImportDataService::$entityManager                               

- [ ] [**Ligne 271**](src/Services/ImportDataService.php#L271): Property App\Services\ImportDataService::$entityManager                               

- [ ] [**Ligne 278**](src/Services/ImportDataService.php#L278): Method                                                                                

- [ ] [**Ligne 305**](src/Services/ImportDataService.php#L305): Method App\Services\ImportDataService::handleImportError() has                        

- [ ] [**Ligne 341**](src/Services/ImportDataService.php#L341): Method App\Services\ImportDataService::countTotalRecords() has                        

- [ ] [**Ligne 368**](src/Services/ImportDataService.php#L368): Method App\Services\ImportDataService::createOrUpdateRadiogramme()                    

- [ ] [**Ligne 417**](src/Services/ImportDataService.php#L417): Parameter #1 $string of function strlen expects string,                               

- [ ] [**Ligne 428**](src/Services/ImportDataService.php#L428): Parameter #1 $string of function strlen expects string,                               

- [ ] [**Ligne 451**](src/Services/ImportDataService.php#L451): Parameter #1 $array of function array_sum expects array,                              

- [ ] [**Ligne 583**](src/Services/ImportDataService.php#L583): Parameter #1 $entityManager of method                                                 

- [ ] [**Ligne 679**](src/Services/ImportDataService.php#L679): Parameter #2 $entityManager of method                                                 

- [ ] [**Ligne 700**](src/Services/ImportDataService.php#L700): Parameter #2 $entityManager of method                                                 

### Problèmes détectés par Psalm



### Problèmes détectés par PHPMD

- [ ] [**Command/CleanRadiogrammeErrorsCommand.php:58**](src/Command/CleanRadiogrammeErrorsCommand.php#L58): CyclomaticComplexity - The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- [ ] [**Command/CleanRadiogrammeErrorsCommand.php:58**](src/Command/CleanRadiogrammeErrorsCommand.php#L58): NPathComplexity - The method execute() has an NPath complexity of 768. The configured NPath complexity threshold is 200.
- [ ] [**Command/CleanRadiogrammeErrorsCommand.php:150**](src/Command/CleanRadiogrammeErrorsCommand.php#L150): UnusedFormalParameter - Avoid unused parameters such as '$limit'.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:21**](src/Command/ExportRadiogrammeErrorsCommand.php#L21): LongVariable - Avoid excessively long variable names like $radiogrammeErrorRepository. Keep variable name length under 20.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:50**](src/Command/ExportRadiogrammeErrorsCommand.php#L50): CyclomaticComplexity - The method execute() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:50**](src/Command/ExportRadiogrammeErrorsCommand.php#L50): NPathComplexity - The method execute() has an NPath complexity of 528. The configured NPath complexity threshold is 200.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:50**](src/Command/ExportRadiogrammeErrorsCommand.php#L50): ExcessiveMethodLength - The method execute() has 114 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:70**](src/Command/ExportRadiogrammeErrorsCommand.php#L70): ElseExpression - The method execute uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Command/ExportRadiogrammeErrorsCommand.php:81**](src/Command/ExportRadiogrammeErrorsCommand.php#L81): StaticAccess - Avoid using static access to class '\League\Csv\Writer' in method 'execute'.
- [ ] [**Entity/Radiogramme.php:12**](src/Entity/Radiogramme.php#L12): ExcessivePublicCount - The class Radiogramme has 49 public methods and attributes. Consider reducing the number of public items to less than 45.
- [ ] [**Entity/Radiogramme.php:12**](src/Entity/Radiogramme.php#L12): TooManyFields - The class Radiogramme has 24 fields. Consider redesigning Radiogramme to keep the number of fields under 15.
- [ ] [**Entity/Radiogramme.php:12**](src/Entity/Radiogramme.php#L12): ExcessiveClassComplexity - The class Radiogramme has an overall complexity of 50 which is very high. The configured complexity threshold is 50.
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
- [ ] [**Services/ImportDataService.php:16**](src/Services/ImportDataService.php#L16): ExcessiveClassComplexity - The class ImportDataService has an overall complexity of 96 which is very high. The configured complexity threshold is 50.
- [ ] [**Services/ImportDataService.php:24**](src/Services/ImportDataService.php#L24): LongVariable - Avoid excessively long variable names like $radiogrammeRepository. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:25**](src/Services/ImportDataService.php#L25): LongVariable - Avoid excessively long variable names like $radiogrammeErrorRepository. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:30**](src/Services/ImportDataService.php#L30): CyclomaticComplexity - The method importData() has a Cyclomatic Complexity of 12. The configured cyclomatic complexity threshold is 10.
- [ ] [**Services/ImportDataService.php:30**](src/Services/ImportDataService.php#L30): ExcessiveMethodLength - The method importData() has 193 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Services/ImportDataService.php:73**](src/Services/ImportDataService.php#L73): LongVariable - Avoid excessively long variable names like $processedUniqueValues. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:76**](src/Services/ImportDataService.php#L76): LongVariable - Avoid excessively long variable names like $successfullyImportedValues. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:128**](src/Services/ImportDataService.php#L128): MissingImport - Missing class import via use statement (line '128', column '33').
- [ ] [**Services/ImportDataService.php:153**](src/Services/ImportDataService.php#L153): ElseExpression - The method importData uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:199**](src/Services/ImportDataService.php#L199): LongVariable - Avoid excessively long variable names like $successfullyImportedCount. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:228**](src/Services/ImportDataService.php#L228): UnusedPrivateMethod - Avoid unused private methods such as 'processBatch'.
- [ ] [**Services/ImportDataService.php:228**](src/Services/ImportDataService.php#L228): UnusedFormalParameter - Avoid unused parameters such as '$progressBar'.
- [ ] [**Services/ImportDataService.php:245**](src/Services/ImportDataService.php#L245): ShortVariable - Avoid variables with short names like $em. Configured minimum length is 3.
- [ ] [**Services/ImportDataService.php:284**](src/Services/ImportDataService.php#L284): MissingImport - Missing class import via use statement (line '284', column '29').
- [ ] [**Services/ImportDataService.php:305**](src/Services/ImportDataService.php#L305): ShortVariable - Avoid variables with short names like $e. Configured minimum length is 3.
- [ ] [**Services/ImportDataService.php:368**](src/Services/ImportDataService.php#L368): CyclomaticComplexity - The method createOrUpdateRadiogramme() has a Cyclomatic Complexity of 22. The configured cyclomatic complexity threshold is 10.
- [ ] [**Services/ImportDataService.php:368**](src/Services/ImportDataService.php#L368): NPathComplexity - The method createOrUpdateRadiogramme() has an NPath complexity of 544. The configured NPath complexity threshold is 200.
- [ ] [**Services/ImportDataService.php:368**](src/Services/ImportDataService.php#L368): ExcessiveMethodLength - The method createOrUpdateRadiogramme() has 155 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Services/ImportDataService.php:380**](src/Services/ImportDataService.php#L380): ElseExpression - The method createOrUpdateRadiogramme uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:406**](src/Services/ImportDataService.php#L406): MissingImport - Missing class import via use statement (line '406', column '37').
- [ ] [**Services/ImportDataService.php:411**](src/Services/ImportDataService.php#L411): ElseExpression - The method createOrUpdateRadiogramme uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:421**](src/Services/ImportDataService.php#L421): MissingImport - Missing class import via use statement (line '421', column '33').
- [ ] [**Services/ImportDataService.php:430**](src/Services/ImportDataService.php#L430): MissingImport - Missing class import via use statement (line '430', column '33').
- [ ] [**Services/ImportDataService.php:435**](src/Services/ImportDataService.php#L435): ElseExpression - The method createOrUpdateRadiogramme uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:450**](src/Services/ImportDataService.php#L450): StaticAccess - Avoid using static access to class '\DateTime' in method 'createOrUpdateRadiogramme'.
- [ ] [**Services/ImportDataService.php:461**](src/Services/ImportDataService.php#L461): MissingImport - Missing class import via use statement (line '461', column '37').
- [ ] [**Services/ImportDataService.php:480**](src/Services/ImportDataService.php#L480): MissingImport - Missing class import via use statement (line '480', column '23').
- [ ] [**Services/ImportDataService.php:508**](src/Services/ImportDataService.php#L508): ElseExpression - The method createOrUpdateRadiogramme uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:531**](src/Services/ImportDataService.php#L531): ElseExpression - The method getExecutionTime uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:542**](src/Services/ImportDataService.php#L542): CyclomaticComplexity - The method persistErrors() has a Cyclomatic Complexity of 29. The configured cyclomatic complexity threshold is 10.
- [ ] [**Services/ImportDataService.php:542**](src/Services/ImportDataService.php#L542): NPathComplexity - The method persistErrors() has an NPath complexity of 589992. The configured NPath complexity threshold is 200.
- [ ] [**Services/ImportDataService.php:542**](src/Services/ImportDataService.php#L542): ExcessiveMethodLength - The method persistErrors() has 179 lines of code. Current threshold is set to 100. Avoid really long methods.
- [ ] [**Services/ImportDataService.php:559**](src/Services/ImportDataService.php#L559): LongVariable - Avoid excessively long variable names like $processedUniqueValues. Keep variable name length under 20.
- [ ] [**Services/ImportDataService.php:566**](src/Services/ImportDataService.php#L566): UnusedLocalVariable - Avoid unused local variables such as '$index'.
- [ ] [**Services/ImportDataService.php:597**](src/Services/ImportDataService.php#L597): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:607**](src/Services/ImportDataService.php#L607): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:619**](src/Services/ImportDataService.php#L619): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:629**](src/Services/ImportDataService.php#L629): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:644**](src/Services/ImportDataService.php#L644): MissingImport - Missing class import via use statement (line '644', column '41').
- [ ] [**Services/ImportDataService.php:661**](src/Services/ImportDataService.php#L661): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:684**](src/Services/ImportDataService.php#L684): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:708**](src/Services/ImportDataService.php#L708): ElseExpression - The method persistErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:749**](src/Services/ImportDataService.php#L749): ElseExpression - The method bulkInsertErrors uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
- [ ] [**Services/ImportDataService.php:789**](src/Services/ImportDataService.php#L789): ElseExpression - The method bulkInsertRadiogrammes uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
### Code dupliqué détecté par PHPCPD

- [ ] **Duplication entre fichiers** :

  ```
Found 3 clones with 296 duplicated lines in 2 files:

  - [src/Entity/Radiogramme.php:150-370 (220 lines)](src/Entity/Radiogramme.php#L150-L370)
  - [src/Entity/Radiogramme.php:88-142 (54 lines)](src/Entity/Radiogramme.php#L88-L142)
  - [src/Entity/Radiogramme.php:44-66 (22 lines)](src/Entity/Radiogramme.php#L44-L66)
  ```


