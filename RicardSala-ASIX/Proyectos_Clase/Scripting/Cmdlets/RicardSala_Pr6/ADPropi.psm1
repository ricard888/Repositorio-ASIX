Import-Module ActiveDirectory

function Get-UsuariState {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string]$NomCompte
    )
    $usuari = Get-ADUser -Filter "SamAccountName -eq '$NomCompte'" -ErrorAction SilentlyContinue
    if ($null -eq $usuari) { return $null }
    if ($usuari.Enabled) { return 'Enabled' }
    return 'Disabled'
}

function Get-Usuaris {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string]$Grupo
    )
    Get-ADGroupMember -Identity $Grupo -Recursive |
        Where-Object { $_.objectClass -eq 'user' } |
        Get-ADUser -Properties EmailAddress, OfficePhone
}

function Get-Alumnes {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $false)]
        [ValidateSet("gAlumnes", "gESO1A", "gASIX2A")]
        [string]$Grupo = "gAlumnes"
    )
    Get-ADGroupMember -Identity $Grupo -Recursive |
        Where-Object { $_.objectClass -eq 'user' } |
        Get-ADUser -Properties EmailAddress, OfficePhone |
        Sort-Object Surname, GivenName |
        Select-Object GivenName, Surname, EmailAddress, OfficePhone
}

function New-Alumne {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)] [string]$NomCompte,
        [Parameter(Mandatory = $true)] [string]$Nom,
        [Parameter(Mandatory = $true)] [string]$Cognoms,
        [Parameter(Mandatory = $true)] [ValidateSet("ESO1A", "ASIX2A")][string]$Grup,
        [Parameter(Mandatory = $true)] [string]$Password,
        [Parameter(Mandatory = $false)] [string]$Descripcio = ""
    )

    if ($Grup -eq "ESO1A") {
        $ouPath = "OU=ESO1A,OU=ESO1,OU=ESO,OU=Alumnes,DC=XYZ,DC=net"
        $grupAD = "gESO1A"
    } elseif ($Grup -eq "ASIX2A") {
        $ouPath = "OU=ASIX2A,OU=ASIX2,OU=ASIX,OU=Alumnes,DC=XYZ,DC=net"
        $grupAD = "gASIX2A"
    }

    $secPassword = ConvertTo-SecureString $Password -AsPlainText -Force

    New-ADUser -SamAccountName $NomCompte -GivenName $Nom -Surname $Cognoms -Name "$Nom $Cognoms" -UserPrincipalName "$NomCompte@XYZ.net" -Path $ouPath -AccountPassword $secPassword -Enabled $true -PasswordNeverExpires $true -Description $Descripcio

    Add-ADGroupMember -Identity $grupAD -Members $NomCompte
}

function New-Profe {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)] [string]$NomCompte,
        [Parameter(Mandatory = $true)] [string]$Nom,
        [Parameter(Mandatory = $true)] [string]$Cognoms,
        [Parameter(Mandatory = $true)] [ValidateSet("Mates")][string]$Departament,
        [Parameter(Mandatory = $true)] [string]$Email,
        [Parameter(Mandatory = $false)] [string]$Descripcio = "",
        [Parameter(Mandatory = $false)] [PSCredential]$SmtpCreds
    )

    $ouPath = "OU=$Departament,OU=Professors,DC=XYZ,DC=net"
    $grupAD = "g$Departament"

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
    $randomPass = -join ((1..8) | ForEach-Object { $chars[(Get-Random -Maximum $chars.Length)] })
    $secPassword = ConvertTo-SecureString $randomPass -AsPlainText -Force

    New-ADUser -SamAccountName $NomCompte -GivenName $Nom -Surname $Cognoms -Name "$Nom $Cognoms" -UserPrincipalName "$NomCompte@XYZ.net" -EmailAddress $Email -Path $ouPath -AccountPassword $secPassword -Enabled $true -PasswordNeverExpires $true -Description $Descripcio

    Add-ADGroupMember -Identity $grupAD -Members $NomCompte

    # Enviament de correu electrònic amb les credencials
    $mailParams = @{
        SmtpServer = "localhost"
        From       = "admin@XYZ.net"
        To         = $Email
        Subject    = "Credencials del compte de professor"
        Body       = "Hola $Nom, el teu usuari es $NomCompte i la teva contrasenya es $randomPass"
    }
    if ($SmtpCreds) { $mailParams["Credential"] = $SmtpCreds }

    try {
        Send-MailMessage @mailParams -ErrorAction SilentlyContinue
    } catch {
        Write-Warning "No s'ha pogut enviar el correu electrònic a $Email"
    }
}

function Create-Alumnes {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string]$RutaCSV
    )

    if (-not (Test-Path $RutaCSV)) {
        Write-Error "El fitxer CSV no existeix a la ruta especificada: $RutaCSV"
        return
    }

    $alumnes = Import-Csv -Path $RutaCSV -Delimiter ";"

    foreach ($alumne in $alumnes) {
        $estat = Get-UsuariState -NomCompte $alumne.NomCompte

        if ($estat -eq "Enabled") {
            Write-Host "L'usuari $($alumne.NomCompte) ja està actiu." -ForegroundColor Yellow
        }
        elseif ($estat -eq "Disabled") {
            Enable-ADAccount -Identity $alumne.NomCompte
            Write-Host "L'usuari $($alumne.NomCompte) ha sigut activat." -ForegroundColor Green
        }
        elseif ($null -eq $estat) {
            New-Alumne -NomCompte $alumne.NomCompte -Nom $alumne.Nom -Cognoms $alumne.Cognoms -Grup $alumne.Grup -Password $alumne.Password -Descripcio $alumne.Descripcio
            Write-Host "L'usuari $($alumne.NomCompte) ha sigut creat correctament." -ForegroundColor Green
        }
    }
}

function Create-Professors {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string]$RutaCSV
    )

    if (-not (Test-Path $RutaCSV)) {
        Write-Error "El fitxer CSV no existeix a la ruta especificada: $RutaCSV"
        return
    }

    # Demana credencials SMTP una sola vegada (complint la V2 de l'enunciat)
    $smtpCreds = Get-Credential -Message "Introdueix les credencials del servidor SMTP per enviar correus"

    $profes = Import-Csv -Path $RutaCSV -Delimiter ";"

    foreach ($profe in $profes) {
        $estat = Get-UsuariState -NomCompte $profe.NomCompte

        if ($estat -eq "Enabled") {
            Write-Host "El professor $($profe.NomCompte) ja està actiu." -ForegroundColor Yellow
        }
        elseif ($estat -eq "Disabled") {
            Enable-ADAccount -Identity $profe.NomCompte
            Write-Host "El professor $($profe.NomCompte) ha sigut activat." -ForegroundColor Green
        }
        elseif ($null -eq $estat) {
            New-Profe -NomCompte $profe.NomCompte -Nom $profe.Nom -Cognoms $profe.Cognoms -Departament $profe.Departament -Email $profe.Email -Descripcio $profe.Descripcio -SmtpCreds $smtpCreds
            Write-Host "El professor $($profe.NomCompte) ha sigut creat correctament." -ForegroundColor Green
        }
    }
}

# SIG # Begin signature block
# MIIFcwYJKoZIhvcNAQcCoIIFZDCCBWACAQExCzAJBgUrDgMCGgUAMGkGCisGAQQB
# gjcCAQSgWzBZMDQGCisGAQQBgjcCAR4wJgIDAQAABBAfzDtgWUsITrck0sYpfvNR
# AgEAAgEAAgEAAgEAAgEAMCEwCQYFKw4DAhoFAAQU3JClwfad7Kz1VxVLj89lfHW5
# KyegggMMMIIDCDCCAfCgAwIBAgIQQ1PVymCWzZ9CP4/WCj0iRTANBgkqhkiG9w0B
# AQsFADAcMRowGAYDVQQDDBFDZXJ0aWZpY2F0QURQcm9waTAeFw0yNjEwMTUyMzU5
# MDRaFw0yNzEwMTYwMDE5MDRaMBwxGjAYBgNVBAMMEUNlcnRpZmljYXRBRFByb3Bp
# MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6mFK2UIgxp3YraJyiSLD
# KbWCnp1VyM0f8a+6Vw8JDNSGCkdNm/CG/qN4+7Z9sg8kIrH5nIj6yM8IKZWecmf/
# ux42tTWa5Ldekq/xKWZZTyWvkZsXoOFVc6QUNw6RC6IdZeIKslCvo2UPNaJjnMpt
# yXbVmqDeE2mWLVSbqf4MUg3QRUFpMAWGfeBRLAframgQJmTjkVMDJCPg7qpaWy+b
# VGXz4nPIYAXshE07VBmCPzIpqn5VwddAOsZNcXXSbAllSAh0iNlecM2oovx0ZF+e
# sBzEBGI9R82h1jTNSXWnq4WpXV9A+5YnNZuH3+YWGLUcHqlJIjPeQEeJ/sPz7Sl2
# 6QIDAQABo0YwRDAOBgNVHQ8BAf8EBAMCB4AwEwYDVR0lBAwwCgYIKwYBBQUHAwMw
# HQYDVR0OBBYEFECxE+5tVs1R5m46m9JfvzrsIvw9MA0GCSqGSIb3DQEBCwUAA4IB
# AQCESYX96OMBplffneQazNO4XPfl2AkWxkCJ4JGUzTnu7dDCp9fbJ2DlIi3Iji+V
# bZ9DKN7HOu32BJRw7az0cIl6gunchtMWsLlIRPsIiJ7ZBJjzHUbW/eCHt/akfCHj
# m7E2UIGCZ3IbV5oPqaLuppiBzk1a2mSU9Jp9+3CZXF3ngXNq95NPzur6/tDVIF0p
# io2y/KJMmu4MfZlJUQvlPFIKv0git2AZzl0LCVkeuF/GAWIO2wHy+u0oUYHbO9ZN
# CuOlzi3rJIoKVxDDFK+S1gpV4PfVZ/ytTLVKpJwBmcWTTQTYnBNowxOQp1X/ZOPy
# L5Wx3SBtSBkT6UNiXbnpC5P0MYIB0TCCAc0CAQEwMDAcMRowGAYDVQQDDBFDZXJ0
# aWZpY2F0QURQcm9waQIQQ1PVymCWzZ9CP4/WCj0iRTAJBgUrDgMCGgUAoHgwGAYK
# KwYBBAGCNwIBDDEKMAigAoAAoQKAADAZBgkqhkiG9w0BCQMxDAYKKwYBBAGCNwIB
# BDAcBgorBgEEAYI3AgELMQ4wDAYKKwYBBAGCNwIBFTAjBgkqhkiG9w0BCQQxFgQU
# IzYrM/dgDUtR4bkqkbG1U3gLdSkwDQYJKoZIhvcNAQEBBQAEggEAWz/5kVgHKQOi
# MTgJKJZnD/1yTUCkpgVCOb9gheoBOFTugqe31YhJSvjsiM/h/Xtm4J6w1XrMaVOP
# l72gYU4D1rJvhJT/mL8b+lsXMhhMK0otB3o/pHetYaHBsUjOxbbYOj6Z2kcRcvY5
# VvLWXnj0fbRbg3xOFMaIeoI6d93QaOAbMeMc1X2vell52NRAyt7fAF+uI00lTwrd
# NOikXK2HpeUZRzYBcLZ/ndNp86vORWMyetlBG5S8vnf5C0PdiAe7RkE3sByTCY9t
# b/ZrflEbvonv18dZ3bnuCA7e0DZcgoHXa5FICunroUhVwMZvlq9+SHNvAzh9ijIz
# cKDKsVxZfQ==
# SIG # End signature block
