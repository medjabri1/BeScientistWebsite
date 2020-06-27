from PyPDF2 import PdfFileMerger
import mysql.connector
import os
import PyPDF2

#generer la page sommaire
os.system("cre_sum.py")
pdfs=[]
pdfs.append("uploads/page1.pdf")
pdfs.append("uploads/summary.pdf")

conn = mysql.connector.connect(host="localhost",user="id13569561_root",password="site-PFE-esto2020", database="id13569561_pfe")
cursor = conn.cursor()
cursor.execute("SELECT article_accepted_id FROM articleaccepted")
rows = cursor.fetchall()
for row in rows:
    row="("+str(row)+")"
    pdf = row[ row.find( '(' )+2 : row.find( ')' )-1 ]
    pdfs.append("uploads/"+str(pdf)+".pdf")

merger = PdfFileMerger()
for files in pdfs:
    merger.append(files)

cursor.execute("SELECT count(*) FROM volume")
vol = cursor.fetchone()
vol="("+str(vol)+")"
v = vol[ vol.find( '(' )+2 : vol.find( ')' )-1 ]
v=int(v)+1
merger.write("volumes/Volume"+str(v)+".pdf")


merger.close()

#convertir le contenu des pdfs au format text
pdffileobj=open("uploads/page1.pdf",'rb')
pdfreader=PyPDF2.PdfFileReader(pdffileobj)
x=pdfreader.numPages
pageobj=pdfreader.getPage(x-x)
contenuG=pageobj.extractText()
pdffileobj.close()
#####################################
pdffileobj=open("uploads/summary.pdf",'rb')
pdfreader=PyPDF2.PdfFileReader(pdffileobj)
x=pdfreader.numPages
n=x
pageobj=pdfreader.getPage(n-x)
contenuS=pageobj.extractText()
x=x-1
while x > 0:
    pageobj=pdfreader.getPage(n-x)
    contenuS=contenuS+pageobj.extractText()
    x=x-1

pdffileobj.close()
############################
pdffileobj=open("volumes/Volume"+str(v)+".pdf",'rb')
pdfreader=PyPDF2.PdfFileReader(pdffileobj)
x=pdfreader.numPages
n=x
pageobj=pdfreader.getPage(n-x+2)
contenuV=pageobj.extractText()
x=x-1
while x > 0:
    pageobj=pdfreader.getPage(n-x)
    contenuV=contenuV+pageobj.extractText()
    x=x-1

pdffileobj.close()
#stocker le contenu des pdfs dans la base de donner
infoV = (contenuV,contenuG,contenuS)
cursor.execute("""INSERT INTO volume (volume_contenu,background,summary) VALUES(%s,%s,%s)""", infoV)

cursor.execute("SELECT article_accepted_id FROM articleaccepted")
rows = cursor.fetchall()
for row in rows:
    cursor.execute("""update article set state='AJ' where article_id=%s""",row)
#VIDER LA TABLE ARTICLEACCETER

cursor.execute("""DELETE FROM articleaccepted """)

conn.commit()

os.remove("uploads/page1.pdf")
os.remove("uploads/summary.pdf")

conn.close()
