create table o1ta_a0shool2daxue from 

(SELECT * FROM o1t1_doc0s1 as t1
LEFT JOIN daxue as t2 ON t1.id = t2.id
UNION
SELECT * FROM o1t1_doc0s1 as t1
RIGHT JOIN daxue as t2 ON t1.id = t2.id
)