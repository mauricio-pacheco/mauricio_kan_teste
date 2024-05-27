import React, { useState, useEffect, useMemo } from 'react';

function formatFileSize(sizeInBytes) {
    if (sizeInBytes < 1024) {
        return sizeInBytes + ' B';
    } else if (sizeInBytes < 1024 * 1024) {
        return (sizeInBytes / 1024).toFixed(2) + ' KB';
    } else {
        return (sizeInBytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
}

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
            setFiles(data.files); 
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
                            <li key={index}>
                                <strong>Nome do Arquivo:</strong> {file.name}, <strong>Tipo:</strong> {file.type}, <strong>Tamanho:</strong> {formatFileSize(file.size)}
                            </li>
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
