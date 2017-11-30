DROP VIEW vipids;
DROP VIEW vip;

--get all the cid's of clients who have ordered more than 4 times
--4 is a trivial number that we decided determines VIP status
CREATE VIEW vipids AS
SELECT cid, COUNT(ono) AS numorders
FROM orders o
GROUP BY cid
HAVING COUNT(ono) > 4;

--using the VIEW vipids, get the rest of the information of a client by joining area and client
CREATE VIEW vip AS
SELECT c.cid, c.cfname, c.clname, c.ccompany, c.street, a.city, a.province, c.postal, c.cphone, numorders
FROM client c, area a, vipids v
WHERE c.postal = a.postal AND v.cid = c.cid
ORDER BY numorders DESC;