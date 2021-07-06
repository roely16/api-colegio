<div>
    <p>Estimado(a): {{ $data->encargado->primer_nombre }} {{ $data->encargado->primer_apellido }}</p>

    <p>Se ha generado la gestión <strong>No. {{ $data->gestion_id }}</strong> para la inscripción del alumno(a): </p>

    <ul>
        <li>
            <strong>Nombre: </strong>
            {{ $data->alumno->primer_nombre }} {{ $data->alumno->primer_apellido }}
        </li>
        <li>
            <strong>Fecha de Nacimiento: </strong>
            {{ $data->alumno->fecha_nacimiento }}
        </li>
    </ul>

    <p>
        Para dar seguimiento al proceso de inscripción puede hacerlo mediante el siguiente link.
    </p>

    <a href="">Seguimiento</a>
</div>