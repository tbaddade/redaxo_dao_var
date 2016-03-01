# DAO Vars

- [Einleitung](#einleitung)
- [Allgemein](#allgemein)
- [Argumente (global)](#argumente)
    - [label](#argumente-label)
    - [output="form"](#argumente-output)
- [Vars](#variablen)
    - [REX_DAO_CATEGORY_SELECT](#rex-dao-category-select)
    - [REX_DAO_LINK](#rex-dao-link)
    - [REX_DAO_SELECT](#rex-dao-select)
    - [REX_DAO_TABLE](#rex-dao-table)
    - [REX_DAO_VALUE](#rex-dao-value)
    
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
    // <label>Meine Bezeichnung</label>


<a name="argumente-output"></a>
### output="form"

Erzeugt eine form-group

    output="form" 
    // `col-md-2` für das Label und `col-md-10` für das Element werden automatisch gesetzt


    output="form:3:9"
    // `col-md-` wird automatisch voran gesetzt
    // (ergibt hier `col-md-3` für das Label und `col-md-9` für das Element)


    output="form:col-sm-4:col-sm-8"
    // Hier wurden direkt Klassen für das Label `col-sm-4` und das Element `col-sm-8` vergeben 

#### Beispiele

**Beispiel #1**

    REX_DAO_VALUE[id="1" label="Mein label" output="form"]
 
 Erzeugt folgende Ausgabe:

    <div class="form-group">
        <label class="col-md-2 control-label">
            Mein label
        </label>
        <div class="col-md-10">
            {{ widget }}
        </div>
    </div>

**Beispiel #2**

    REX_DAO_VALUE[id="1" label="Mein label" output="form:3:9"]
 
 Erzeugt folgende Ausgabe:

    <div class="form-group">
        <label class="col-md-3 control-label">
            Mein label
        </label>
        <div class="col-md-9">
            {{ widget }}
        </div>
    </div>

**Beispiel #3**

    REX_DAO_VALUE[id="1" label="Mein label" output="form:col-xs-4:col-xs-8"]
 
 Erzeugt folgende Ausgabe:

    <div class="form-group">
        <label class="col-xs-4 control-label">
            Mein label
        </label>
        <div class="col-xs-8">
            {{ widget }}
        </div>
    </div>


## Vars

<a name="rex-dao-category-select"></a>
### `REX_DAO_CATEGORY_SELECT`

#### Moduleingabe

| Argument | Werte              | Beschreibung |
|:-------- |:------------------ |:------------ |
| root     | integer(s)         | Kategorie-Id oder mehrere Kategorie-Ids kommasepariert als Wurzelelemente der Select-Box |
| multiple | bool               | Mehrfachauswahl |

**Beispiele**

    REX_DAO_CATEGORY_SELECT[id="1" widget="1"]
    REX_DAO_CATEGORY_SELECT[id="1" widget="1" root="5"]
    REX_DAO_CATEGORY_SELECT[id="1" widget="1" root="5,6"]
    REX_DAO_CATEGORY_SELECT[id="1" widget="1" root="5" multiple="1"]
    
    // Mit Anwendung der globalen Argumente 
    REX_DAO_CATEGORY_SELECT[id="1" widget="1" root="5" output="form:3:9" label="Kategorie auswählen"]


#### Modulausgabe
**Beispiele**

    REX_DAO_CATEGORY_SELECT[id="1"]
    // returns
    // 10
    // ["5","10","9"] (multiple)



<a name="rex-dao-link"></a>
### `REX_DAO_LINK`

initial dasselbe Verhalten wie `REX_LINK[id="1"]`

#### Moduleingabe

| Argument | Werte (default) | Beschreibung                         |
|:-------- |:--------------- |:------------------------------------ |
| linkmap  | bool (1)        | Option um die Linkmap aufzurufen     |
| text     | bool (0)        | Option um einen Linktext einzutragen |
| url      | bool (0)        | Option um eine Url einzutragen       |

**Beispiele**

    REX_DAO_LINK[id="1" widget="1"]
    REX_DAO_LINK[id="1" widget="1" text="1"]
    REX_DAO_LINK[id="1" widget="1" text="1" url="1"]
    
    // Mit Anwendung der globalen Argumente 
    REX_DAO_LINK[id="1" widget="1" text="1" url="1" output="form:3:9" label="Link setzen"]

#### Modulausgabe

| Argument | Werte (default)              | Beschreibung                      |
|:-------- |:---------------------------- |:--------------------------------- |
| output   | string [`link`, `url`] (url) | gibt einen Link oder eine Url aus |
| class    | string                       | Gibt die angegeben CSS-Class aus  |

**Beispiele**

    REX_DAO_LINK[id="1"]
    REX_DAO_LINK[id="1" output="url"]
    REX_DAO_LINK[id="1" output="link"]
    REX_DAO_LINK[id="1" output="link" class="btn btn-primary"]



<a name="rex-dao-select"></a>
### `REX_DAO_SELECT`

#### Moduleingabe

| Argument | Werte        | Beschreibung                      |
|:-------- |:------------ |:--------------------------------- |
| options  | string       | Angabe der `<optgroup>` und `<option>` (Pipe, Doppelpunkt, Komma separiert) |

**Beispiel #1** einzelne options

    REX_DAO_SELECT[id="1" widget="1" options="Auswahl,Dänemark=dk,Deutschland=de,Kanada=ca,USA=us"]
    
    // Erzeugt folgende Ausgabe    
    <select>
        <option value="">Auswahl</option>
        <option value="dk">Dänemark</option>
        <option value="de">Deutschland</option>
        <option value="ca">Kanada</option>
        <option value="us">USA</option>
    </select>
    
**Beispiel #2** options mit optgroups

    REX_DAO_SELECT[id="1" widget="1" options="Auswahl|Europa:Dänemark=dk,Deutschland=de|Amerika:Kanada=ca,USA=us"]
    
    // Erzeugt folgende Ausgabe    
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

    REX_DAO_SELECT[id="1" widget="1" options="SELECT name, code FROM rex_country ORDER BY name"]
    
    // Erzeugt folgende Ausgabe
    <select>
        <option value="dk">Dänemark</option>
        <option value="de">Deutschland</option>
        <option value="ca">Kanada</option>
        <option value="us">USA</option>
    </select>

**Beispiel #4** options mit optgroup via SQL-Query

    REX_DAO_SELECT[id="1" widget="1" options="Europa:SELECT name, code FROM rex_country WHERE continent=eu ORDER BY name|America:SELECT name, code FROM rex_country WHERE continent=na ORDER BY name"]
    
    // Erzeugt folgende Ausgabe
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


<a name="rex-dao-table"></a>
### `REX_DAO_TABLE`

#### Moduleingabe

| Argument | Werte       | Beschreibung                                               |
|:-------- |:----------- |:---------------------------------------------------------- |
| cols     | integer     | Anzahl der Tabellenspalten                                 |
| rows     | integer     | Anzahl der Tabellenreihen im `<tbody>`                     |
| tcols    | integer     | TitleCOLS - Anzahl der Tabellenspalten `<th>` im `<tbody>` |
| trows    | integer     | TitleROWS - Anzahl der Tabellenzeilen `<tr>` im `<thead>`  |
| frows    | integer     | FooterROWS - Anzahl der Tabellenzeilen im `<tfoot>`        |

 
**Beispiel #1**

    REX_DAO_TABLE[id="1" widget="1" cols="3" rows="2"]
    
    // Erzeugt folgende Ausgabe    
    <table>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
**Beispiel #2**

    REX_DAO_TABLE[id="1" widget="1" cols="3" rows="2" tcols="1"]
    
    // Erzeugt folgende Ausgabe    
    <table>
        <tbody>
            <tr>
                <th></th>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th></th>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    


<a name="rex-dao-value"></a>
### `REX_DAO_VALUE`

#### Moduleingabe

| Argument | Werte (default)                             | Beschreibung                      |
|:-------- |:------------------------------------------- |:--------------------------------- |
| type     | string [`text`, `textarea`, `email`] (text) |                                   |

**Beispiel #1** `text`

    REX_DAO_VALUE[id="1" widget="1"]
    
    // Erzeugt folgende Ausgabe    
    <input class="form-control" type="text" ... />
    
**Beispiel #2** `textarea`

    REX_DAO_VALUE[id="1" widget="1" type="textarea"]    
    
    //Erzeugt folgende Ausgabe    
    <textarea class="form-control" ... > ... </textarea>

**Beispiel #3** `email`

    REX_DAO_VALUE[id="1" widget="1" type="email"]
    
    // Erzeugt folgende Ausgabe    
    <input class="form-control" type="email" ... />
