<?php
// auteur : Rémi
// cette page permet de savoir qui affronte qui par défaut (sans les nom)

// on initialise les joueurs des matchs par défaut, utilisé si le joueur n'est pas encore rentré
$joueursMatchDefaut[0]=array("rang 1", "rang 32", NULL, NULL);
$joueursMatchDefaut[1]=array("rang 17", "rang 16", NULL, NULL);
$joueursMatchDefaut[2]=array("rang 9", "rang 24", NULL, NULL);
$joueursMatchDefaut[3]=array("rang 25", "rang 8", NULL, NULL);
$joueursMatchDefaut[4]=array("rang 5", "rang 28", NULL, NULL);
$joueursMatchDefaut[5]=array("rang 21", "rang 12", NULL, NULL);
$joueursMatchDefaut[6]=array("rang 13", "rang 20", NULL, NULL);
$joueursMatchDefaut[7]=array("rang 29", "rang 4", NULL, NULL);
$joueursMatchDefaut[8]=array("rang 3", "rang 30", NULL, NULL);
$joueursMatchDefaut[9]=array("rang 19", "rang 14", NULL, NULL);
$joueursMatchDefaut[10]=array("rang 11", "rang 22", NULL, NULL);
$joueursMatchDefaut[11]=array("rang 27", "rang 6", NULL, NULL);
$joueursMatchDefaut[12]=array("rang 7", "rang 26", NULL, NULL);
$joueursMatchDefaut[13]=array("rang 23", "rang 10", NULL, NULL);
$joueursMatchDefaut[14]=array("rang 15", "rang 18", NULL, NULL);
$joueursMatchDefaut[15]=array("rang 31", "rang 2", NULL, NULL);
$joueursMatchDefaut[16]=array("Vainqueurs Match 1", "Vainqueurs Match 2", 1, 2);
$joueursMatchDefaut[17]=array("Vainqueurs Match 3", "Vainqueurs Match 4", 3, 4);
$joueursMatchDefaut[18]=array("Vainqueurs Match 5", "Vainqueurs Match 6", 5, 6);
$joueursMatchDefaut[19]=array("Vainqueurs Match 7", "Vainqueurs Match 8", 7, 8);
$joueursMatchDefaut[20]=array("Vainqueurs Match 9", "Vainqueurs Match 10", 9, 10);
$joueursMatchDefaut[21]=array("Vainqueurs Match 11", "Vainqueurs Match 12", 11, 12);
$joueursMatchDefaut[22]=array("Vainqueurs Match 13", "Vainqueurs Match 14", 13, 14);
$joueursMatchDefaut[23]=array("Vainqueurs Match 15", "Vainqueurs Match 16", 15, 16);
$joueursMatchDefaut[24]=array("Perdant Match 1", "Perdant Match 2", 1, 2);
$joueursMatchDefaut[25]=array("Perdant Match 3", "Perdant Match 4", 3, 4);
$joueursMatchDefaut[26]=array("Perdant Match 5", "Perdant Match 6", 5, 6);
$joueursMatchDefaut[27]=array("Perdant Match 7", "Perdant Match 8", 7, 8);
$joueursMatchDefaut[28]=array("Perdant Match 9", "Perdant Match 10", 9, 10);
$joueursMatchDefaut[29]=array("Perdant Match 11", "Perdant Match 12", 11, 12);
$joueursMatchDefaut[30]=array("Perdant Match 13", "Perdant Match 14", 13, 14);
$joueursMatchDefaut[31]=array("Perdant Match 15", "Perdant Match 16", 15, 16);
$joueursMatchDefaut[32]=array("Vainqueurs Match 25", "Perdant Match 24", 25, 24);
$joueursMatchDefaut[33]=array("Vainqueurs Match 26", "Perdant Match 23", 26, 23);
$joueursMatchDefaut[34]=array("Vainqueurs Match 27", "Perdant Match 22", 27, 22);
$joueursMatchDefaut[35]=array("Vainqueurs Match 28", "Perdant Match 21", 28, 21);
$joueursMatchDefaut[36]=array("Vainqueurs Match 29", "Perdant Match 20", 29, 20);
$joueursMatchDefaut[37]=array("Vainqueurs Match 30", "Perdant Match 19", 30, 19);
$joueursMatchDefaut[38]=array("Vainqueurs Match 31", "Perdant Match 18", 31, 18);
$joueursMatchDefaut[39]=array("Vainqueurs Match 32", "Perdant Match 17", 32, 17);
$joueursMatchDefaut[40]=array("Vainqueurs Match 17", "Vainqueurs Match 18", 17, 18);
$joueursMatchDefaut[41]=array("Vainqueurs Match 19", "Vainqueurs Match 20", 19, 20);
$joueursMatchDefaut[42]=array("Vainqueurs Match 21", "Vainqueurs Match 22", 21, 22);
$joueursMatchDefaut[43]=array("Vainqueurs Match 23", "Vainqueurs Match 24", 23, 24);
$joueursMatchDefaut[44]=array("Perdant Match 25", "Perdant Match 26", 25, 26);
$joueursMatchDefaut[45]=array("Perdant Match 27", "Perdant Match 28", 27, 28);
$joueursMatchDefaut[46]=array("Perdant Match 29", "Perdant Match 30", 29, 30);
$joueursMatchDefaut[47]=array("Perdant Match 31", "Perdant Match 32", 31, 32);
$joueursMatchDefaut[48]=array("Perdant Match 33", "Perdant Match 34", 33, 34);
$joueursMatchDefaut[49]=array("Perdant Match 35", "Perdant Match 36", 35, 36);
$joueursMatchDefaut[50]=array("Perdant Match 37", "Perdant Match 38", 37, 38);
$joueursMatchDefaut[51]=array("Perdant Match 39", "Perdant Match 40", 39, 40);
$joueursMatchDefaut[52]=array("Vainqueurs Match 33", "Vainqueurs Match 34", 33, 34);
$joueursMatchDefaut[53]=array("Vainqueurs Match 35", "Vainqueurs Match 36", 35, 36);
$joueursMatchDefaut[54]=array("Vainqueurs Match 37", "Vainqueurs Match 38", 37, 38);
$joueursMatchDefaut[55]=array("Vainqueurs Match 39", "Vainqueurs Match 40", 39, 40);
$joueursMatchDefaut[56]=array("Perdant Match 45", "Perdant Match 46", 45, 46);
$joueursMatchDefaut[57]=array("Perdant Match 47", "Perdant Match 48", 47, 48);
$joueursMatchDefaut[58]=array("Perdant Match 49", "Perdant Match 50", 49, 50);
$joueursMatchDefaut[59]=array("Perdant Match 51", "Perdant Match 52", 51, 52);
$joueursMatchDefaut[60]=array("Vainqueurs Match 53", "Perdant Match 42", 53, 42);
$joueursMatchDefaut[61]=array("Vainqueurs Match 54", "Perdant Match 41", 54, 41);
$joueursMatchDefaut[62]=array("Vainqueurs Match 55", "Perdant Match 44", 55, 44);
$joueursMatchDefaut[63]=array("Vainqueurs Match 56", "Perdant Match 43", 56, 43);
$joueursMatchDefaut[64]=array("Vainqueurs Match 45", "Vainqueurs Match 46", 45, 46);
$joueursMatchDefaut[65]=array("Vainqueurs Match 47", "Vainqueurs Match 48", 47, 48);
$joueursMatchDefaut[66]=array("Vainqueurs Match 49", "Vainqueurs Match 50", 49, 50);
$joueursMatchDefaut[67]=array("Vainqueurs Match 51", "Vainqueurs Match 52", 51, 41);
$joueursMatchDefaut[68]=array("Vainqueurs Match 41", "Vainqueurs Match 42", 41, 42);
$joueursMatchDefaut[69]=array("Vainqueurs Match 43", "Vainqueurs Match 44",43, 44);
$joueursMatchDefaut[70]=array("Vainqueurs Match 61", "Vainqueurs Match 62", 61, 62);
$joueursMatchDefaut[71]=array("Vainqueurs Match 63", "Vainqueurs Match 64", 63, 64);
$joueursMatchDefaut[72]=array("Perdant Match 66", "Vainqueurs Match 57", 66, 57);
$joueursMatchDefaut[73]=array("Perdant Match 65", "Vainqueurs Match 58", 65, 58);
$joueursMatchDefaut[74]=array("Perdant Match 68", "Vainqueurs Match 59", 68, 59);
$joueursMatchDefaut[75]=array("Perdant Match 67", "Vainqueurs Match 60", 67, 60);
$joueursMatchDefaut[76]=array("Perdant Match 70", "Vainqueurs Match 71", 70, 71);
$joueursMatchDefaut[77]=array("Perdant Match 69", "Vainqueurs Match 72", 69, 72);
$joueursMatchDefaut[78]=array("Vainqueurs Match 65", "Vainqueurs Match 73", 65, 73);
$joueursMatchDefaut[79]=array("Vainqueurs Match 66", "Vainqueurs Match 74", 66, 74);
$joueursMatchDefaut[80]=array("Vainqueurs Match 67", "Vainqueurs Match 75", 67, 75);
$joueursMatchDefaut[81]=array("Vainqueurs Match 68", "Vainqueurs Match 76", 68, 76);
$joueursMatchDefaut[82]=array("Perdant Match 53", "Perdant Match 54", 53, 54);
$joueursMatchDefaut[83]=array("Perdant Match 55", "Perdant Match 56", 55, 56);
$joueursMatchDefaut[84]=array("Perdant Match 61", "Perdant Match 62", 61, 62);
$joueursMatchDefaut[85]=array("Perdant Match 63", "Perdant Match 64", 63, 64);
$joueursMatchDefaut[86]=array("Vainqueurs Match 69", "Vainqueurs Match 77", 69, 77);
$joueursMatchDefaut[87]=array("Vainqueurs Match 70", "Vainqueurs Match 78", 70, 78);
$joueursMatchDefaut[88]=array("Perdant Match 57", "Perdant Match 58", 57, 58);
$joueursMatchDefaut[89]=array("Perdant Match 73", "Perdant Match 74", 73, 74);
$joueursMatchDefaut[90]=array("Perdant Match 79", "Perdant Match 80", 79, 80);
$joueursMatchDefaut[91]=array("Vainqueurs Match 79", "Vainqueurs Match 80", 79, 80);
$joueursMatchDefaut[92]=array("Perdant Match 59", "Perdant Match 60", 59, 60);
$joueursMatchDefaut[93]=array("Perdant Match 75", "Perdant Match 76", 75, 76);
$joueursMatchDefaut[94]=array("Perdant Match 81", "Perdant Match 82", 81, 82);
$joueursMatchDefaut[95]=array("Vainqueurs Match 81", "Vainqueurs Match 82", 81, 82);
$joueursMatchDefaut[96]=array("Perdant Match 83", "Perdant Match 84", 83, 84);
$joueursMatchDefaut[97]=array("Vainqueurs Match 83", "Vainqueurs Match 84", 83, 84);
$joueursMatchDefaut[98]=array("Perdant Match 85", "Perdant Match 86", 85, 86);
$joueursMatchDefaut[99]=array("Vainqueurs Match 85", "Vainqueurs Match 86", 85, 86);
$joueursMatchDefaut[100]=array("Perdant Match 71", "Perdant Match 72", 71, 72);
$joueursMatchDefaut[101]=array("Perdant Match 77", "Perdant Match 78", 77, 78);
$joueursMatchDefaut[102]=array("Perdant Match 87", "Perdant Match 88", 87, 88);
$joueursMatchDefaut[103]=array("Vainqueurs Match 87", "Vainqueurs Match 88", 87, 88);
?>