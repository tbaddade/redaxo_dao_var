# DAO Vars

- [Einleitung](#einleitung)
- [Allgemein](#allgemein)
- [Argumente (global)](#argumente)
    - [label](#argumente-label)
    - [output="form"](#argumente-output)
- [Vars](#variablen)
    - [REX_DAO_CATEGORY_SELECT](#variablen-category-select)
    - [REX_DAO_LINK](#variablen-link)
    - [REX_DAO_SELECT](#variablen-select)
    - [REX_DAO_VALUE](#variablen-value)
    
<a name="einleitung"></a>
## Einleitung

DAO_VARS sind zusätzliche REX_VARS des [CMS REDAXO](https://github.com/redaxo/redaxo/) ab Version 5.

<a name="allgemein"></a>
## Allgemein

DAO_VARS verwenden immer die value(n) Felder der Tabelle. Das bedeutet, dass die `id` nur einmal verwendet werden darf.

**Falsch:** Hier würde das letzte DAO_VAR das vorherige überschreiben
    
    REX_DAO_SELECT[id="1"]
    REX_DAO_VALUE[id="1"]

**Richtig:**

    REX_DAO_SELECT[id="1"]
    REX_DAO_VALUE[id="2"]


<a name="argumente"></a>
## Argumente (global)

<a name="argumente-label"></a>
### label

Erzeugt ein `<label>` 

    label="Meine Bezeichnung"

--

<a name="argumente-output"></a>
### output="form"

Erzeugt eine form-group

    output="form [: mixin $classLabel=col-md-2 : mixin $classElement=col-md-10]"

#### Beispiele

**Beispiel #1**

    REX_DAO_VAR[output="form"]
 
 Erzeugt folgende Ausgabe:

    <div class="form-group">
        <label class="col-md-2 control-label">
            {{ arg.label }}
        </label>
        <div class="col-md-2">
            {{ widget }}
        </div>
    </div>

**Beispiel #2**

    REX_DAO_VAR[output="form:col-xs-4:col-xs-8"]
 
 Erzeugt folgende Ausgabe:

    <div class="form-group">
        <label class="col-xs-4 control-label">
            {{ arg.label }}
        </label>
        <div class="col-xs-8">
            {{ widget }}
        </div>
    </div>

**Beispiel #3**

    REX_DAO_VAR[output="form:3:9"]
 
 Erzeugt folgende Ausgabe:

    <div class="form-group">
        <label class="col-md-3 control-label">
            {{ arg.label }}
        </label>
        <div class="col-md-9">
            {{ widget }}
        </div>
    </div>


## Vars

<a name="variablen-category-select"></a>
### REX_DAO_CATEGORY_SELECT

#### Moduleingabe

| Argument | Werte              | Beschreibung |
|:-------- |:------------------ |:------------ |
| root     | Id, Array von Ids  | Kategorie-Id oder ein Array von Kategorie-Ids als Wurzelelemente der Select-Box |

**Beispiele**

```
REX_DAO_CATEGORY_SELECT[id="1"]
```
    
```
REX_DAO_CATEGORY_SELECT[id="1" root="5"]
```

#### Modulausgabe
**Beispiele**

    REX_DAO_CATEGORY_SELECT[id="1"]

--

<a name="variablen-link"></a>
### REX_DAO_LINK

#### Moduleingabe
**Beispiele**

    REX_DAO_LINK[id="1"]

#### Modulausgabe

| Argument | Werte        | Beschreibung                      |
|:-------- |:------------ |:--------------------------------- |
| output   | `link` `url` | gibt einen Link oder eine Url aus |
| class    | string       | Gibt die angegeben CSS-Class aus  |

**Beispiele**

```
REX_DAO_LINK[id="1"]
```
```
REX_DAO_LINK[id="1" output="url"]
```
```
REX_DAO_LINK[id="1" output="link"]
```
```
REX_DAO_LINK[id="1" output="link" class="btn btn-primary"]
```

--

<a name="variablen-select"></a>
### REX_DAO_SELECT

#### Moduleingabe

| Argument | Werte        | Beschreibung                      |
|:-------- |:------------ |:--------------------------------- |
| options  | string       | Angabe der `<optgroup>` und `<option>` (Komma, Doppelpunkt, Pipe separiert) |

**Beispiel #1** einzelne options

    REX_DAO_SELECT[id="1" options="Auswahl,Dänemark=dk,Deutschland=de,Kanada=ca,USA=us"]
    
Erzeugt folgende Ausgabe
    
    <select>
        <option value="">Auswahl</option>
        <option value="dk">Dänemark</option>
        <option value="de">Deutschland</option>
        <option value="ca">Kanada</option>
        <option value="us">USA</option>
    </select>
    
**Beispiel #2** options mit optgroup

    REX_DAO_SELECT[id="1" options="Auswahl|Europa:Dänemark=dk,Deutschland=de|Amerika:Kanada=ca,USA=us"]
    
Erzeugt folgende Ausgabe
    
    <select>
        <option value="">Auswahl</option>
        <optgroup label="Europa">
            <option value="dk">Dänemark</option>
            <option value="de">Deutschland</option>
        </optgroup>
        <optgroup label="Amerika">
            <option value="ca">Kanada</option>
            <option value="us">USA</option>
        </optgroup>
    </select>

**Beispiel #3** options via SQL-Query

    REX_DAO_SELECT[id="1" options="SELECT name, code FROM rex_country ORDER BY name"]
    
Erzeugt folgende Ausgabe

    <select>
        <option value="dk">Dänemark</option>
        <option value="de">Deutschland</option>
        <option value="ca">Kanada</option>
        <option value="us">USA</option>
    </select>

**Beispiel #4** options mit optgroup via SQL-Query

    REX_DAO_SELECT[id="1" options="Europa:SELECT name, code FROM rex_country WHERE continent=eu ORDER BY name|America:SELECT name, code FROM rex_country WHERE continent=na ORDER BY name"]
    
Erzeugt folgende Ausgabe

    <select>
        <optgroup label="Europa">
            <option value="dk">Dänemark</option>
            <option value="de">Deutschland</option>
        </optgroup>
        <optgroup label="Amerika">
            <option value="ca">Kanada</option>
            <option value="us">USA</option>
        </optgroup>
    </select>



#### Modulausgabe

    REX_DAO_SELECT[id="1"]

--

<a name="variablen-value"></a>
### REX_DAO_VALUE

#### Moduleingabe

| Argument | Werte        | Beschreibung                      |
|:-------- |:------------ |:--------------------------------- |
| type     | string `text` `textarea` `email` | default: `text` |

**Beispiel #1** `text`

    REX_DAO_VALUE[id="1"]
    
Erzeugt folgende Ausgabe
    
    <input class="form-control" type="text" ... />
    
**Beispiel #2** `textarea`

    REX_DAO_VALUE[id="1" type="textarea"]
    
Erzeugt folgende Ausgabe
    
    <textarea class="form-control" ... > ... </textarea>

**Beispiel #3** `email`

    REX_DAO_VALUE[id="1" type="email"]
    
Erzeugt folgende Ausgabe
    
    <input class="form-control" type="email" ... />
