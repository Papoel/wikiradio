#!/usr/bin/awk -f
BEGIN { 
    current_file = "";
    in_error = 0;
    # Supprimer les lignes de progression existantes
    print "" > "phpstan-filtered.tmp";
}

# Ignorer les lignes de progression et les lignes vides
/^\s*[0-9]+\/[0-9]+\s*\[▓/ || /^$/ { next }
/^\s*--+\s+--+\s*$/ { next }
/^\s*\[ERROR\]/ { next }

# Capturer les noms de fichiers
/^\s*Line\s+/ {
    current_file = $2;
    print "#### [" current_file "](src/" current_file ")\n" >> "phpstan-filtered.tmp";
    next;
}

# Traiter les lignes d'erreur
/^\s*[0-9]+\s+/ {
    # Extraire le numéro de ligne et le message
    line_num = $1;
    $1 = "";
    message = $0;
    
    # Écrire dans le fichier temporaire
    print line_num ":" message >> "phpstan-filtered.tmp";
    next;
}

# Capturer les lignes de documentation
/^\s*💡/ || /^\s*http/ {
    print $0 >> "phpstan-filtered.tmp";
    next;
}

END {
    # Traiter le fichier filtré
    current_file = "";
    line_num = "";
    message = "";
    doc_link = "";
    
    while ((getline line < "phpstan-filtered.tmp") > 0) {
        if (line ~ /^####/) {
            # Nouveau fichier
            current_file = line;
            print current_file >> "TODO-BEFORE-COMMIT.md";
            print "" >> "TODO-BEFORE-COMMIT.md";
        } else if (line ~ /^[0-9]+:/) {
            # Nouvelle erreur
            split(line, parts, ":");
            line_num = parts[1];
            message = substr(line, index(line, ":") + 1);
            
            # Nettoyer le message
            gsub(/^\s+/, "", message);
            
            # Écrire l'erreur
            print "- [ ] [**Ligne " line_num "**](src/" substr(current_file, 7, index(current_file, "]") - 7) "#L" line_num "):" message >> "TODO-BEFORE-COMMIT.md";
        } else if (line ~ /💡/) {
            # Ligne de documentation
            print "  💡 [Documentation](https://phpstan.org/developing-extensions/always-read-written-properties)" >> "TODO-BEFORE-COMMIT.md";
            print "" >> "TODO-BEFORE-COMMIT.md";
        } else {
            # Continuation du message précédent
            if (line_num != "") {
                gsub(/^\s+/, "", line);
                print "  " line >> "TODO-BEFORE-COMMIT.md";
            }
        }
    }
    
    # Supprimer le fichier temporaire
    system("rm phpstan-filtered.tmp");
}
