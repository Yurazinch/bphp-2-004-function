<?php

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_EDIT = 4;
const OPERATION_QUANTITY = 5;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_EDIT => OPERATION_EDIT . '. Изменить товар.',
    OPERATION_QUANTITY => OPERATION_QUANTITY . '. Изменить количество.'
];

$items = [];

function toPrint(string $a): void 
{    
    echo PHP_EOL . $a . PHP_EOL;    
}

function toString(array $b): void
{
    echo implode(PHP_EOL, $b) . PHP_EOL;
}

function toStringItems(array $items): void
{
    foreach ($items as $item => $value) 
    {
        toPrint("Наименование: {$item}, шт.: {$value}");
    }
}

function addItem(string $a): void
{
    global $items;
    
    $toAdd = explode(',', $a);
    if (empty($toAdd[0])) {
		toPrint('Не ввели название товара');
		return;
	}
    $item = (string)trim($toAdd[0]);
    if(isset($toAdd[1]) || array_key_exists(1, $toAdd)) {
        $value = trim($toAdd[1]);
    } else {
        $value = 0;
    }
    if (is_numeric($value) !== false) {
        $items[$item] = (int)$value;
        if($items[$item] < 0) { 
            $items[$item] = 0;
        }
    } else {
        toPrint('Правильно укажите количество');
    }
}

function deleteItem(string $c): void // удаление товара
{
    global $items;

    if (array_key_exists(trim($c), $items) !== false) 
    {        
        unset($items[trim($c)]);      
    }
    else
    {
        toPrint('Введите правильное название');
    }
}

function editItem(string $c): void // изменение названия
{
    global $items;

    $toChange = explode(',', $c); 
    $key = (string)trim($toChange[0]);
    $item = trim($toChange[1]);
    if(array_key_exists($key, $items) !== false) 
    {
        unset($items[$key]);
        toPrint('Введите количество для добавления в список:');
        $value = trim(fgets(STDIN));
        if (is_numeric($value) === true)
        {
            $items[$item] = (int)$value;
            if($items[$item] < 0)
            {
                $items[$item] = 0;
            }
        }
        else
        {
            toPrint('Укажите количество числом');
        }
    }

}

function addQuantity(string $c): void // изменить количество
{

    global $items;

    $toQuantity = explode(',', $c);
    $item = trim($toQuantity[0]);
    if($toQuantity[1])
    $value = trim($toQuantity[1]);
    if(array_key_exists($item, $items) !== false)
    {
        if(is_numeric($value) === true)
        {
            $value = (int)$value;
            $items[$item] += $value;
            if($items[$item] < 0)
            {
                $items[$item] = 0;
            }
        }
        else 
        {
            toPrint('Количество указано неправильно.');
        }
    }
    else
    {
        toPrint('Такого товара нет в списке.');
    }
    
}


do {
    system('clear');
            //    system('cls'); // windows

    do {
        if (count($items)) {
            toPrint('Ваш список покупок: ');
            toStringItems($items);
        } else {
            toPrint('Ваш список покупок пуст.');
        }

        toPrint('Выберите операцию для выполнения: ');
        // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
        toString($operations);
        $operationNumber = trim(fgets(STDIN));

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');            
            toPrint('!!! Неизвестный номер операции, повторите попытку.');
        }

    } while (!array_key_exists($operationNumber, $operations));

    $operation = "Выбрана операция: $operations[$operationNumber]";
    toPrint($operation);

    switch ($operationNumber) {
        case OPERATION_ADD:
            toPrint('Введите название товара и количество через запятую для добавления в список:');
            $itemName = trim(fgets(STDIN));
            addItem($itemName);            
            break;

        case OPERATION_DELETE:
            // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
            toPrint('Текущий список покупок:');
            toPrint('Список покупок: ');
            toStringItems($items);
            toPrint('Введение название товара для удаления из списка:');
            echo '> ';
            $itemName = trim(fgets(STDIN));
            deleteItem($itemName);            
            break;

        case OPERATION_PRINT:
            toPrint('Ваш список покупок: ');
            toStringItems($items);
            $count = count($items);
            $n = "Всего  $count позиций.";
            toPrint($n);
            toPrint('Нажмите enter для продолжения');
            fgets(STDIN);
            break;

        case OPERATION_EDIT:
            toPrint('Введите название товара для изменения и новое название через запятую:');
            echo '> ';
            $names = trim(fgets(STDIN));
            editItem($names);            
            break;            

        case OPERATION_QUANTITY:
            toPrint('Введите название товара и количеcтво через запятую:');
            echo '> ';
            $withQuantity = trim(fgets(STDIN));
            addQuantity($withQuantity);                       
            break; 
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

toPrint('Программа завершена');

?>