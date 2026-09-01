-- create_view_rel_inch_worker_stage.sql
-- This view provides a short identifier (<=30 chars) for the long table name
-- Rel_Incharge_Worker_ProductionStage, which exceeds Oracle 11g's identifier limit.
-- The view simply selects all columns from the original table.

CREATE OR REPLACE VIEW Rel_Inch_Worker_Stage AS
    SELECT * FROM Rel_Incharge_Worker_ProductionStage;

