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
    OPERATION_QUANTITY => OPERATION_QUANTITY . '. Добавить количество.'
];

$items = [];

function toPrint(string $a): void 
{
    echo $a . PHP_EOL;
}

function toString(array $b): void
{
    echo implode(PHP_EOL, $b) . PHP_EOL;
}

function deleteItem(string $c): void // удаление товара
{
    global $items;

    if (in_array($c, $items, true) !== false) {
        while (($key = array_search($c, $items, true)) !== false) {
            unset($items[$key]);
        }
    }
}

function editItem(string $c): void // изменение названия
{
    global $items;

    $toChange = explode(',', $c);            
    if(in_array($toChange[0], $items, true) !== false) {
        while (($key = array_search(trim($toChange[0]), $items, true)) !== false) {
            $items[$key] = trim($toChange[1]);
        }
    }
}

function addQuantity(string $c): void // добавить количество
{

    global $items;

    $toQuantity = explode(',', $c);
    if(is_numeric(trim($toQuantity[1])) === true) {
        $toQuantity[1] = (int)trim($toQuantity[1]);                
        if(in_array(trim($toQuantity[0]), $items, true) !== false) {            
            while (($key = array_search(trim($toQuantity[0]), $items, true)) === false) {
                $items[$key] = trim($toQuantity[0]) . ": штук: $toQuantity[1]";
                /*if(is_array($items[$key]) !== false) {
                    $items[$key][$toQuantity[0]] += $toQuantity[1];                            
                } else {
                    $items[$key] = [trim($toQuantity[0]) => $toQuantity[1]];
                }*/   
            } 
        } else {
            toPrint('Такого товара нет в списке.');
        }
    } else {
        toPrint('Количество указано неправильно.');
    }
   
}


do {
    system('clear');
            //    system('cls'); // windows

    do {
        if (count($items)) {
            toPrint('Ваш список покупок: ');
            toString($items);
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
            toPrint('Введение название товара для добавления в список:');
            $itemName = trim(fgets(STDIN));
            $items[] = $itemName;
            break;

        case OPERATION_DELETE:
            // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
            toPrint('Текущий список покупок:');
            toPrint('Список покупок: ');
            toString($items);

            toPrint('Введение название товара для удаления из списка:');
            echo '> ';
            $itemName = trim(fgets(STDIN));
            deleteItem($itemName);
            
            break;

        case OPERATION_PRINT:
            toPrint('Ваш список покупок: ');
            toString($items);
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