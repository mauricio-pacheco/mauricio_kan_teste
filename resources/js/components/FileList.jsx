import React, { useState, useEffect, useMemo } from 'react';

function FileList({ updateFiles }) {
    const [files, setFiles] = useState([]);

    useEffect(() => {
        fetchFiles();
    }, [updateFiles]);

    const fetchFiles = async () => {
        try {
            const response = await fetch('/api/files');
            if (!response.ok) {
                throw new Error('Erro ao obter a lista de arquivos.');
            }
            const data = await response.json();
            setFiles(data.files.map(file => file.split('/').pop())); // Formatando o nome do arquivo removendo o caminho completo
        } catch (error) {
            console.error('Erro ao obter a lista de arquivos:', error);
        }
    };

    const fileListContent = useMemo(() => {
        return (
            <div>
                <h2>Lista de Arquivos CSV</h2>
                <ul>
                    {files.length > 0 ? (
                        files.map((file, index) => (
                            <li key={index}>{file}</li>
                        ))
                    ) : (
                        <li>Nenhum arquivo encontrado.</li>
                    )}
                </ul>
            </div>
        );
    }, [files]);

    return fileListContent;
}

export default FileList;
