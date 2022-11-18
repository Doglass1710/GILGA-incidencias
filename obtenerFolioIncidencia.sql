use bdgilga

delimiter //
create procedure obtenerFolioInicidencia(
	in INestacion varchar(10),
    in INtipo_solicitud varchar(15)/*,
    out OUTfolio varchar(10)*/
)
begin
	declare max_folio int;
    declare OUTfolio varchar(10);
    
    select max(convert(substring(folio,5),unsigned integer)) into max_folio 
	from incidencias
	where estacion = INestacion and
          tipo_solicitud = INtipo_solicitud;
     
     if max_folio is null then
		set max_folio = 0;
     end if;
     
     set OUTfolio = concat(substring(INtipo_solicitud,1,3),'-', convert(max_folio + 1,char(10)));
     select OUTfolio;
			
end//
delimiter ;

call obtenerFolioInicidencia('6620','incidencia');
select @OUTfolio;

drop procedure obtenerFolioInicidencia;